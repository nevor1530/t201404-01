<?php

class ExamPointController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/function';
	public $examBankId;
	public $examBankName;
	public $subjects;
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
		$this->initial($exam_bank_id, $subject_id);
		if ($subject_id == 0 && count($this->subjects) == 0) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else if ($subject_id == 0) {
			$subject_id = $this->subjects[0]['id'];
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
	
	public function actionNewPractise($exam_bank_id, $subject_id, $exam_point_id) {
		$this->initial($exam_bank_id, $subject_id);
		$examPoint = ExamPointModel::model()->findByPk($exam_point_id);
		$examPointName = $examPoint->name;
		
		$candidateQuestionIds = array();
		$this->getQuestionIdsByExamPoint($examPoint, $candidateQuestionIds);
		
		$selectedQuestionIds = array();
		if (count($candidateQuestionIds) > 0) {
			$selectedQuestionIds = array_rand($candidateQuestionIds, min(count($candidateQuestionIds), 15));
			$favoriteQuestionIds = $this->getFavoriteQuestionIds($selectedQuestionIds);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("question_id", $selectedQuestionIds);
		$criteria->order = 'material_id, question_id desc';
		$questionRecords = QuestionModel::model()->findAll($criteria);	
		
		$questions = array();
		if ($questionRecords != null) {
			$examPaperInstanceModel = new ExamPaperInstanceModel;
			$examPaperInstanceModel->exam_paper_id = 0;
			$examPaperInstanceModel->exam_point_id = $exam_point_id;
			$examPaperInstanceModel->user_id = $userId = Yii::app()->user->id;
			$examPaperInstanceModel->start_time = date("Y-m-d H:i:s");
			$examPaperInstanceModel->elapsed_time = 0;
			$examPaperInstanceModel->is_completed = 0;
			
			if ($examPaperInstanceModel->validate() && $examPaperInstanceModel->save()) {
				for ($index = 0; $index < count($questionRecords); $index++) {
					$questionModel = $questionRecords[$index];
					
					$questionInstanceModel = new QuestionInstanceModel;
					$questionInstanceModel->exam_paper_instance_id = $examPaperInstanceModel->exam_paper_instance_id;
					$questionInstanceModel->question_id = $questionModel->question_id;
					$questionInstanceModel->user_id = $userId = Yii::app()->user->id;
					if ($questionInstanceModel->validate()) {
						$questionInstanceModel->save();
					}
					
					$questions[$index]['questionInstanceId'] = $questionInstanceModel->question_instance_id;
					$questions[$index]['questionId'] = $questionModel->question_id;
					$questions[$index]['content'] = $questionModel->questionExtra->title;
					$questions[$index]['answerOptions'] = array();
					$questions[$index]['questionType'] = $questionModel->question_type;
					if ($questionModel->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $questionModel->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE) {
						$questionAnswerOptions = $questionModel->questionAnswerOptions;
						foreach ($questionAnswerOptions as $questionAnswerOption) {
							$questions[$index]['answerOptions'][] = array(
								'index' =>$questionAnswerOption->attributes['index'],
								'description' => $questionAnswerOption->attributes['description'],
								'isSelected' => false,
							);
						}
					}  else if ($questionModel->question_type == QuestionModel::TRUE_FALSE_TYPE) {
						$questions[$index]['answerOptions'][] = array('index' => '0', 'description' => '正确', 'isSelected' => false);
						$questions[$index]['answerOptions'][] = array('index' => '1', 'description' => '错误', 'isSelected' => false);
					}
					
					if (in_array($questions[$index]['questionId'], $favoriteQuestionIds)) {
						$questions[$index]['is_favorite'] = true;
					} else {
						$questions[$index]['is_favorite'] = false;
					}
			
					$material_id = $questionModel->material_id;
					if ($material_id != 0) {
						$materialModel = MaterialModel::model()->findByPk($material_id);
						if ($materialModel != null) {
							$questions[$index]['material_id'] = $material_id;
							$questions[$index]['material_content'] = $materialModel->content;
						}
					}
				}
			}
			
			//header("Content-type: text/html; charset=utf8"); 
			//print_r($questions);exit();
		
			$this->render('practise', array(
				'examBankId' => $exam_bank_id,
				'subjectId' => $subject_id,
				'examPointName' => $examPointName,
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'elapsedTime' => $examPaperInstanceModel->elapsed_time,
				'questions' => $questions,
			));
		} else {
			$this->redirect(array('index', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
		}
	}
	
	public function actionContinuePractise($exam_bank_id, $subject_id, $exam_paper_instance_id) {
		$this->initial($exam_bank_id, $subject_id);
		
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
				$questionIds = array();
				$index = 0;
				foreach ($questionInstanceModels as $questionInstanceModel) {
					$questionModel = QuestionModel::model()->findByPk($questionInstanceModel->question_id);	
					if ($questionModel == null) {
						continue;
					}
					
					$questionIds[] = $questionInstanceModel->question_id;
					$questions[$index]['questionInstanceId'] = $questionInstanceModel->question_instance_id;
					$questions[$index]['questionId'] = $questionInstanceModel->question_id;
					$questions[$index]['content'] = $questionModel->questionExtra->title;
					$questions[$index]['answerOptions'] = array();
					$questions[$index]['questionType'] = $questionModel->question_type;
					
					$myAnswers = array();
					if ($questionInstanceModel->myanswer != null) {
						$myAnswers = explode("|", $questionInstanceModel->myanswer);
					}
					
					if ($questionModel->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $questionModel->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE) {
						$questionAnswerOptions = $questionModel->questionAnswerOptions;
						foreach ($questionAnswerOptions as $questionAnswerOption) {
							$questions[$index]['answerOptions'][] = array(
								'index' => $questionAnswerOption->index,
								'description' => $questionAnswerOption->description,
								'isSelected' => in_array($questionAnswerOption->index, $myAnswers),
							);
						}
					}  else if ($questionModel->question_type == QuestionModel::TRUE_FALSE_TYPE) {
						$questions[$index]['answerOptions'][] = array('index' => '0', 'description' => '正确', 'isSelected' => in_array(0, $myAnswers));
						$questions[$index]['answerOptions'][] = array('index' => '1', 'description' => '错误', 'isSelected' => in_array(1, $myAnswers));
					}
					
					$material_id = $questionModel->material_id;
					if ($material_id != 0) {
						$materialModel = MaterialModel::model()->findByPk($material_id);
						if ($materialModel != null) {
							$questions[$index]['material_id'] = $material_id;
							$questions[$index]['material_content'] = $materialModel->content;
						}
					}
					
					$index++;
				}
				
				$favoriteQuestionIds = $this->getFavoriteQuestionIds($questionIds);
				foreach ($questions as $question) {
					if (in_array($question['questionId'], $favoriteQuestionIds)) {
						$question['is_favorite'] = true;
					} else {
						$question['is_favorite'] = false;
					}
				}
				
				$this->render('practise', array(
					'examBankId' => $exam_bank_id,
					'subjectId' => $subject_id,
					'examPointName' => $examPointName,
					'examPaperInstanceId' => $exam_paper_instance_id,
					'elapsedTime' => $examPaperInstanceModel->elapsed_time,
					'questions' => $questions,
				));
				Yii::app()->end();
			}
		}
	}
	
	public function actionAjaxAddQustionToFavorites($question_id) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('user_id = ' . Yii::app()->user->id);  
		$criteria->addCondition('question_id = ' . $question_id);  
		$results = QuestionFavoritesModel::model()->findAll($criteria);
		if ($results != null && count($results) > 0) {
			foreach ($results as $record) {
				$record->delete();
			}
			echo json_encode(array('status'=>0, 'action'=>'cancel'));
			Yii::app()->end();
		}
		
		$questionFavoritesModel = new QuestionFavoritesModel;
		$questionFavoritesModel->user_id = Yii::app()->user->id;
		$questionFavoritesModel->question_id = $question_id;
		if ($questionFavoritesModel->validate()) {
			$questionFavoritesModel->save();
			echo json_encode(array('status'=>0, 'action' => 'add'));
		} else {
			echo json_encode(array('status'=>1, 'errMsg'=>CHtml::errorSummary($questionFavoritesModel)));
		}
		Yii::app()->end();
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
			if ($examPaperInstanceModel != null && $examPaperInstanceModel->user_id == $userId) {
				if ($time > $examPaperInstanceModel->elapsed_time) {
					$examPaperInstanceModel->elapsed_time = $time;
					$examPaperInstanceModel->save();
				}
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
	
	public function actionCompletePractise($exam_bank_id, $subject_id, $exam_paper_instance_id) {
		$userId = Yii::app()->user->id;
		$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($exam_paper_instance_id);
		if ($examPaperInstanceModel != null  && $examPaperInstanceModel->user_id == $userId) {
			$examPaperInstanceModel->is_completed = 1;
			$examPaperInstanceModel->save();
		}
		
		$this->redirect(array('index', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
	}
	
	private function getFavoriteQuestionIds($questionIds) {
		$criteria = new CDbCriteria();
		$criteria->condition = 'user_id = ' . Yii::app()->user->id;  
		$criteria->addInCondition('question_id', $questionIds);  
		$results = QuestionFavoritesModel::model()->findAll($criteria);
		
		$favoriteQuestionIds = array();
		if ($results != null) {
			foreach ($results as $record) {
				$favoriteQuestionIds[] = $record->question_id;
			}
		}
		return $favoriteQuestionIds;
	}
	
	private function initial($exam_bank_id, $subject_id) {
		$this->curTab = Constants::$EXAM_POINT_TAB;
		$examBankRecord = ExamBankModel::model()->findByPk($exam_bank_id);
		$this->examBankName = $examBankRecord->name;
		$this->examBankId = $exam_bank_id;
		
		$subjects = array();
		$subjectRecords = $examBankRecord->subjects;
		if ($subjectRecords != null) {
			for ($i = 0; $i < count($subjectRecords); $i++) {
				$subjectRecord = $subjectRecords[$i];
				$subjects[] = array(
					'id' => $subjectRecord->subject_id,
					'name' => $subjectRecord->name,
					'is_current' => (($subject_id == 0 && $i == 0) || $subject_id == $subjectRecord->subject_id),
				);
			}
		}
		$this->subjects = $subjects;
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
			$result[$i]['correct_question_count'] = $this->calCorrectQuestionCount($userId, $result[$i]['exam_point_ids']);
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
	
	private function getQuestionIdsByExamPoint($examPoint, &$candidateQuestionIds) {
		$questionIds = $this->getQuestionIdsByExamPointId($examPoint->exam_point_id);
		$candidateQuestionIds = array_merge($candidateQuestionIds, $questionIds);
		
		$subExamPoints = $examPoint->subExamPoints;
		if (!empty($subExamPoints)) {
			foreach ($subExamPoints as $subExamPoint) {
				$this->getQuestionIdsByExamPoint($subExamPoint, $candidateQuestionIds);
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
	
	private function calCorrectQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question,question_exam_point WHERE " .
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer=question.answer AND " . 
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
