<?php

class ExamPointController extends FunctionController
{
	public $curTab;
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'newPractise', 'ajaxAddQustionToFavorites', 'ajaxSubmitAnswer', 'completePractise', 'continuePractise'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id, Constants::$EXAM_POINT_TAB);
		if ($subject_id == 0 && count($this->subjects) == 0) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else if ($subject_id == 0) {
			$subject_id = $this->subjects[0]['id'];
			$this->curSubjectId = $subject_id;
		}
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointRecords = ExamPointModel::model()->top()->findAll($criteria);
		
		$examPoints = array();
		$this->getExamPoints($examPointRecords, $examPoints);
		
		$result = array(
			'examBankId' => $exam_bank_id,
			'subjectId' => $subject_id,
			'examPoints' => $examPoints,
		);
		
		$this->render('index', $result);
	}
	
	public function actionNewPractise($exam_bank_id, $subject_id, $exam_point_id, $return_url = null) {
		$this->initial($exam_bank_id, $subject_id, Constants::$EXAM_POINT_TAB);
		$examPoint = ExamPointModel::model()->findByPk($exam_point_id);
		$examPointName = $examPoint->name;
		
		$candidateQuestionIds = array();
		$this->getQuestionIdsByExamPoint($examPoint, $candidateQuestionIds);
		
		$selectedQuestionIds = array();
		if (count($candidateQuestionIds) > 0) {
			$selectedQuestionIds = $this->randArray($candidateQuestionIds, 15);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("question_id", $selectedQuestionIds);
		$criteria->order = 'material_id, question_id desc';
		$questionRecords = QuestionModel::model()->findAll($criteria);	
		
		$questions = array();
		if ($questionRecords != null) {
			$examPaperInstanceModel = new ExamPaperInstanceModel;
			$examPaperInstanceModel->instance_type = ExamPaperInstanceModel::NORMAL_PRACTISE_TYPE;
			$examPaperInstanceModel->exam_paper_id = 0;
			$examPaperInstanceModel->exam_point_id = $exam_point_id;
			$examPaperInstanceModel->user_id = Yii::app()->user->id;
			$examPaperInstanceModel->start_time = date("Y-m-d H:i:s");
			$examPaperInstanceModel->elapsed_time = 0;
			$examPaperInstanceModel->is_completed = 0;
			
			if ($examPaperInstanceModel->validate() && $examPaperInstanceModel->save()) {
				$userId = Yii::app()->user->id;
				for ($index = 0; $index < count($questionRecords); $index++) {
					$questionModel = $questionRecords[$index];
					
					$questionInstanceModel = new QuestionInstanceModel;
					$questionInstanceModel->exam_paper_instance_id = $examPaperInstanceModel->exam_paper_instance_id;
					$questionInstanceModel->question_id = $questionModel->question_id;
					$questionInstanceModel->user_id = Yii::app()->user->id;
					if ($questionInstanceModel->validate()) {
						$questionInstanceModel->save();
					}
					
					$question = $this->getQuestionDetailFromModel($questionModel, null, false);
					$question['questionInstanceId'] = $questionInstanceModel->question_instance_id;
					$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionModel->question_id);
					$questions[$index] = $question;
				}
			}
			
			//header("Content-type: text/html; charset=utf8"); 
			//print_r($questions);exit();
		
			$this->render('practise', array(
				'returnUrl' => $return_url,
				'practiseName' => '专项训练：【' . $examPointName . '】',
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'elapsedTime' => $examPaperInstanceModel->elapsed_time,
				'questions' => $questions,
			));
		} else {
			if ($return_url != null) {
				$this->redirect(urldecode($return_url));
			} else {
				$this->redirect(array('index', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
			}
		}
	}
	
	public function actionContinuePractise($exam_bank_id, $subject_id, $exam_paper_instance_id, $return_url = null) {
		$this->initial($exam_bank_id, $subject_id, Constants::$EXAM_POINT_TAB);
		
		$userId = Yii::app()->user->id;
		$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($exam_paper_instance_id);
		if ($examPaperInstanceModel != null && $examPaperInstanceModel->user_id == $userId && 
			$examPaperInstanceModel->is_completed == 0) {
			$examPointId = $examPaperInstanceModel->exam_point_id;
			$examPoint = ExamPointModel::model()->findByPk($examPointId);
			if ($examPointId != null) {
				$examPointName = $examPoint->name;
			}
			
			$criteria = new CDbCriteria();
			$criteria->addCondition('user_id = ' . $userId);  
			$criteria->addCondition('exam_paper_instance_id = ' . $exam_paper_instance_id);  
			$criteria->order = 'question_instance_id asc';
			$questionInstanceModels = QuestionInstanceModel::model()->findAll($criteria);
			if ($questionInstanceModels != null) {
				$questions = array();
				$index = 0;
				foreach ($questionInstanceModels as $questionInstanceModel) {
					$myAnswer = array();
					if ($questionInstanceModel->myanswer != null) {
						$myAnswer = explode("|", $questionInstanceModel->myanswer);
					}
					
					$questionModel = QuestionModel::model()->findByPk($questionInstanceModel->question_id);	
					if ($questionModel == null) {
						continue;
					}
					
					$question = $this->getQuestionDetailFromModel($questionModel, $myAnswer, false);
					$question['questionInstanceId'] = $questionInstanceModel->question_instance_id;
					$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionModel->question_id);
					
					$questions[$index] = $question;
					$index++;
				}
				
				$this->render('practise', array(
					'examBankId' => $exam_bank_id,
					'subjectId' => $subject_id,
					'returnUrl' => $return_url,
					'practiseName' => '专项训练：【' . $examPointName . '】',
					'examPaperInstanceId' => $exam_paper_instance_id,
					'elapsedTime' => $examPaperInstanceModel->elapsed_time,
					'questions' => $questions,
				));
				Yii::app()->end();
			}
		} else {
			if ($return_url != null) {
				$this->redirect(urldecode($return_url));
			} else {
				$this->redirect(array('index', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
			}	
		}
	}
	
	public function actionAjaxSubmitAnswer() {
		if(isset($_POST['answerForm'])) {
			$userId = Yii::app()->user->id;
			$examPaperInstanceId = $_POST['answerForm']['examPaperInstanceId'];
			$questionInstanceId = $_POST['answerForm']['questionInstanceId'];
			$questionId = $_POST['answerForm']['questionId'];
			$answer = $_POST['answerForm']['answer'];
			$time = $_POST['answerForm']['time'];
			
			$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($examPaperInstanceId);
			if ($examPaperInstanceModel != null) {
				if ($examPaperInstanceModel->user_id != $userId) {
					echo json_encode(array('status'=>1, 'errMsg'=>'请您重新登录'));
					Yii::app()->end();
				}
				
				if ($examPaperInstanceModel->is_completed == 1) {
					echo json_encode(array('status'=>1, 'errMsg'=>'试卷已提交，不能继续作答'));
					Yii::app()->end();
				}
				
				if ($time > $examPaperInstanceModel->elapsed_time) {
					$examPaperInstanceModel->elapsed_time = $time;
					$examPaperInstanceModel->save();
				} 
			} else {
				echo json_encode(array('status'=>1, 'errMsg'=>'request page does not exist'));
				Yii::app()->end();
			}
			
			$questionInstanceModel = QuestionInstanceModel::model()->findByPk($questionInstanceId);
			if ($questionInstanceModel != null && $questionInstanceModel->user_id == $userId &&
				$questionInstanceModel->exam_paper_instance_id == $examPaperInstanceId &&
				$questionInstanceModel->question_id = $questionId) {
				$answer = explode(",", $answer);
				$questionInstanceModel->myanswer = implode("|", $answer);
				
				$questionInstanceModel->save();
				echo json_encode(array('status'=>0));
				Yii::app()->end();
			} else {
				echo json_encode(array('status'=>1, 'errMsg'=>CHtml::errorSummary($questionInstanceModel)));
				Yii::app()->end();
			}
		} 
	}
	
	public function actionCompletePractise($exam_bank_id, $subject_id, $exam_paper_instance_id, $return_url = null) {
		$userId = Yii::app()->user->id;
		$this->completePractise($userId, $exam_paper_instance_id);
		if ($return_url != null) {
			$this->redirect(urldecode($return_url));
		} else {
			$this->redirect(array('index', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
		}		
	}
	
	private function getExamPoints($examPointRecords, &$result) {
		if ($examPointRecords == null || count($examPointRecords) == 0) {
			return;
		}
		
		for ($i = 0; $i < count($examPointRecords); $i++) {
			$examPointRecord = $examPointRecords[$i];
			$examPointId = $examPointRecord->exam_point_id;
			$result[$i] = array(
				'id' => $examPointId,
				'name' => $examPointRecord->name,
				'exam_point_ids' => array($examPointId),
			);
			
			$curExamPointQuestionIds = $this->getQuestionIdsByExamPointId($examPointId);
			
			if (!empty($examPointRecord->subExamPoints)){
				$subExamPoints = array();
				$this->getExamPoints($examPointRecord->subExamPoints, $subExamPoints);
				$result[$i]['sub_exam_points'] = $subExamPoints;
				$subExamPointIds = array();
				foreach ($subExamPoints as $subExamPoint) {
					$curExamPointQuestionIds = array_merge($curExamPointQuestionIds, $subExamPoint['question_ids']);
					$subExamPointIds[] = $subExamPoint['id'];
				}
				$result[$i]['exam_point_ids'] = array_merge($result[$i]['exam_point_ids'], $subExamPointIds);
			} else {
				$result[$i]['sub_exam_points'] = array();
			}
			
			$curExamPointQuestionIds = array_unique($curExamPointQuestionIds);
			$result[$i]['question_ids'] = $curExamPointQuestionIds;
			$result[$i]['question_count'] = count($curExamPointQuestionIds);
			
			$userId = Yii::app()->user->id;
			$result[$i]['finished_question_count'] = $this->getFinishedQuestionCount($userId, $result[$i]['exam_point_ids']);
			$result[$i]['correct_question_count'] = $result[$i]['finished_question_count'] - $this->calIncorrectQuestionCount($userId, $result[$i]['exam_point_ids']);
		}
	}
	
	private function getQuestionIdsByExamPointId($examPointId) {
		$questionIds = array();
		$criteria = new CDbCriteria();
		$criteria->condition = 'exam_point_id = ' . $examPointId;  
		$records = QuestionExamPointModel::model()->findAll($criteria);	
		if ($records != null) {
			foreach ($records as $record) {
				$questionIds[] = $record->question_id;
			}
		}
		return $questionIds;
	}
	
	private function getQuestionIdsByExamPoint($examPoint, &$result) {
		$questionIds = $this->getQuestionIdsByExamPointId($examPoint->exam_point_id);
		$result = array_merge($result, $questionIds);
		
		$subExamPoints = $examPoint->subExamPoints;
		if (!empty($subExamPoints)) {
			foreach ($subExamPoints as $subExamPoint) {
				$this->getQuestionIdsByExamPoint($subExamPoint, $result);
			}
		}
	}
	
	private function getFinishedQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question_exam_point WHERE " .
					"question_instance.myanswer IS NOT NULL AND " . 
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_exam_point.exam_point_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
	private function calIncorrectQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question,question_exam_point WHERE " .
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer!=question.answer AND " . 
					"question_exam_point.exam_point_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
}
