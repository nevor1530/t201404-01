<?php
class RealExamPaperController extends FunctionController
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
				'actions'=>array('list', 'newPractise', 'ajaxSubmitAnswer', 'continuePractise', 'ajaxAddQustionToFavorites', 'completePractise', 'viewAnalysis'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionList($exam_bank_id, $subject_id, $is_recommendation = true) {
		$this->initial($exam_bank_id, $subject_id, Constants::$REAL_EXAM_PAPER_TAB);
		
		$examPaperModels = array();
		if ($is_recommendation) {
			$criteria = new CDbCriteria();
			$criteria->addCondition('subject_id = ' . $subject_id);  
			$count = PaperRecommendationModel::model()->count($criteria);  
			
			$pager = new CPagination($count);    
			$pager->pageSize = 5;             
			$pager->applyLimit($criteria);  
			
			$paperRecommendationModels = PaperRecommendationModel::model()->findAll($criteria);
			if ($paperRecommendationModels != null && count($paperRecommendationModels) > 0) {
				$examPaperIds = array();
				foreach ($paperRecommendationModels as $paperRecommendationModel) {
					$examPaperIds[] = $paperRecommendationModel->exam_paper_id;
				}
				
				$criteria = new CDbCriteria();
				$criteria->addInCondition('exam_paper_id', $examPaperIds);  
				$criteria->addCondition('is_real = 1');  
				$criteria->addCondition('status = '. ExamPaperModel::STATUS_PUBLISHED);
				$examPaperModels = ExamPaperModel::model()->findAll($criteria);
			}
		} else {
			$criteria = new CDbCriteria();
			$criteria->addCondition('subject_id = ' . $subject_id);  
			$criteria->addCondition('is_real = 1');  
			$criteria->addCondition('status = '. ExamPaperModel::STATUS_PUBLISHED);
			$count = ExamPaperModel::model()->count($criteria);    
			
			$pager = new CPagination($count);    
			$pager->pageSize = 2;             
			$pager->applyLimit($criteria);  
			
			$examPaperModels = ExamPaperModel::model()->findAll($criteria);
		}
		
		$realExamPapers = array();
		if ($examPaperModels != null) {
			$userId = Yii::app()->user->id;
			foreach ($examPaperModels as $examPaperModel) {
				$realExamPapers[] = array(
					'id' => $examPaperModel->exam_paper_id,
					'name' => $examPaperModel->name,
					'recommendation_value' => $examPaperModel->recommendation,
					'practise_times' => $this->getRealExamPaperPractiseTimes($examPaperModel->exam_paper_id, $userId),
				);
			}
		}
		
			
		$this->render('list',array(
			'isRecommendation' => $is_recommendation,
			'pages'=>$pager,
			'realExamPapers' => $realExamPapers
		));
		
	}
	
	public function actionNewPractise($exam_bank_id, $subject_id, $exam_paper_id, $return_url = null) {
		$this->initial($exam_bank_id, $subject_id, Constants::$REAL_EXAM_PAPER_TAB);
		
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if ($examPaperModel == null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$examPaperInstanceModel = new ExamPaperInstanceModel;
		$examPaperInstanceModel->instance_type = ExamPaperInstanceModel::REAL_EXAM_PAPER_TYPE;
		$examPaperInstanceModel->exam_paper_id = $exam_paper_id;
		$examPaperInstanceModel->exam_point_id = 0;
		$examPaperInstanceModel->user_id = $userId = Yii::app()->user->id;
		$examPaperInstanceModel->start_time = date("Y-m-d H:i:s");
		$examPaperInstanceModel->elapsed_time = 0;
		$examPaperInstanceModel->is_completed = 0;
		
		if ($examPaperInstanceModel->validate() && $examPaperInstanceModel->save()) {
			$criteria = new CDbCriteria();
			$criteria->addCondition('exam_paper_id = ' . $exam_paper_id);  
			$criteria->order = 'sequence asc';
			$questionBlockModels = QuestionBlockModel::model()->findAll($criteria);	
			
			$questionBlocks = array();
			if ($questionBlockModels != null) {
				for ($i = 0; $i < count($questionBlockModels) ;$i++) {
					$questionBlockModel = $questionBlockModels[$i];
					$question_block_id = $questionBlockModel->question_block_id;
					
					$questionBlocks[] = array(
						'id' => $questionBlockModel->question_block_id,
						'name' => $questionBlockModel->name,
						'description' => $questionBlockModel->description,
						'questions' => $this->getQuestions($exam_paper_id, $question_block_id),
					);
				}	
			}
			
			$this->render('practise',array(
				'returnUrl' => $return_url,
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'remainTime' =>  $examPaperModel->time_length,
				'paperName' => $examPaperModel->name,
				'questionBlocks' => $questionBlocks,
			));
		}  else {
			if ($return_url != null) {
				$this->redirect(urldecode($return_url));
			} else {
				$this->redirect(array('recommendation', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
			}
		}
	}
	
	public function actionContinuePractise($exam_bank_id, $subject_id, $exam_paper_instance_id, $return_url = null) {
		$this->initial($exam_bank_id, $subject_id, Constants::$REAL_EXAM_PAPER_TAB);
		
		$userId = Yii::app()->user->id;
		$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($exam_paper_instance_id);
		if ($examPaperInstanceModel != null && $examPaperInstanceModel->user_id == $userId && 
			$examPaperInstanceModel->is_completed == 0) {
			$exam_paper_id = $examPaperInstanceModel->exam_paper_id;
			$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
			if ($examPaperModel == null) {
				throw new CHttpException(404,'The requested page does not exist.');
			}
				
			$criteria = new CDbCriteria();
			$criteria->addCondition('exam_paper_id = ' . $exam_paper_id);  
			$criteria->order = 'sequence asc';
			$questionBlockModels = QuestionBlockModel::model()->findAll($criteria);	
			
			$questionBlocks = array();
			if ($questionBlockModels != null) {
				for ($i = 0; $i < count($questionBlockModels) ;$i++) {
					$questionBlockModel = $questionBlockModels[$i];
					$question_block_id = $questionBlockModel->question_block_id;
					
					$questionBlocks[] = array(
						'id' => $questionBlockModel->question_block_id,
						'name' => $questionBlockModel->name,
						'description' => $questionBlockModel->description,
						'questions' => $this->getQuestions($exam_paper_id, $question_block_id, $exam_paper_instance_id),
					);
				}	
			}
			
			$this->render('practise',array(
				'returnUrl' => $return_url,
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'remainTime' =>  $examPaperModel->time_length - $examPaperInstanceModel->elapsed_time,
				'paperName' => $examPaperModel->name,
				'questionBlocks' => $questionBlocks,
			));
			Yii::app()->end();
		} else {
			if ($return_url != null) {
				$this->redirect(urldecode($return_url));
			} else {
				$this->redirect(array('recommendation', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
			}	
		}
	}
	
	public function actionAjaxSubmitAnswer() {
		if(isset($_POST['answerForm'])) {
			$userId = Yii::app()->user->id;
			$examPaperInstanceId = $_POST['answerForm']['examPaperInstanceId'];
			$questionId = $_POST['answerForm']['questionId'];
			$remainTime = $_POST['answerForm']['remainTime'];
			
			$answer = $_POST['answerForm']['answer'];
			$answer = explode(",", $answer);
			$answer= implode("|", $answer);
			
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
				
				$examPaperModel = ExamPaperModel::model()->findByPk($examPaperInstanceModel->exam_paper_id);
				if ($examPaperModel != null) {
					$examPaperTimeLength = $examPaperModel->time_length;
					$elapsedTime = $examPaperTimeLength - $remainTime;
					if ($elapsedTime > $examPaperInstanceModel->elapsed_time) {
						$examPaperInstanceModel->elapsed_time = $elapsedTime;
						$examPaperInstanceModel->save();
					}
				}
			}
			
			$criteria = new CDbCriteria();
			$criteria->addCondition('exam_paper_instance_id = ' . $examPaperInstanceId);
			$criteria->addCondition('question_id = ' . $questionId);
			$criteria->addCondition('user_id = ' . $userId);
			$questionInstanceModels = QuestionInstanceModel::model()->findAll($criteria);
			if ($questionInstanceModels != null) {
				$questionInstanceModel = $questionInstanceModels[0];
				$questionInstanceModel->myanswer = $answer;
				$questionInstanceModel->save();
			} else {
				$questionInstanceModel = new QuestionInstanceModel;
				$questionInstanceModel->exam_paper_instance_id = $examPaperInstanceId;
				$questionInstanceModel->question_id = $questionId;
				$questionInstanceModel->user_id = $userId;
				$questionInstanceModel->myanswer = $answer;
				$questionInstanceModel->save();
			}
			
			echo json_encode(array('status'=>0));
			Yii::app()->end();
		} 
	}
	
	public function actionCompletePractise($exam_bank_id, $subject_id, $exam_paper_instance_id, $return_url = null) {
		$userId = Yii::app()->user->id;
		$this->completePractise($userId, $exam_paper_instance_id);
		if ($return_url != null) {
			$this->redirect(urldecode($return_url));
		} else {
			$this->redirect(array('list', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
		}	
	}
	
	public function actionViewAnalysis($exam_bank_id, $subject_id, $exam_paper_instance_id) {
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		
		$userId = Yii::app()->user->id;
		$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($exam_paper_instance_id);
		if ($examPaperInstanceModel != null && $examPaperInstanceModel->user_id == $userId && 
			$examPaperInstanceModel->is_completed == 1) {
			$exam_paper_id = $examPaperInstanceModel->exam_paper_id;
			$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
			if ($examPaperModel == null) {
				throw new CHttpException(404,'The requested page does not exist.');
			}
				
			$criteria = new CDbCriteria();
			$criteria->addCondition('exam_paper_id = ' . $exam_paper_id);  
			$criteria->order = 'sequence asc';
			$questionBlockModels = QuestionBlockModel::model()->findAll($criteria);	
			
			$questionBlocks = array();
			if ($questionBlockModels != null) {
				for ($i = 0; $i < count($questionBlockModels) ;$i++) {
					$questionBlockModel = $questionBlockModels[$i];
					$question_block_id = $questionBlockModel->question_block_id;
					
					$questions = $this->getQuestions($exam_paper_id, $question_block_id, $exam_paper_instance_id, true, true);
					for ($index = 0; $index < count($questions); $index++) {
						$question = $questions[$index];
						$questionModel = QuestionModel::model()->findByPk($question['questionId']);	
						$question['my_answer'] = $this->getQuestionAnswer($question['questionId'], $exam_paper_instance_id);
						$question['correct_answer'] = $questionModel->answer;
						$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionModel->question_id);
						$question['is_correct'] = ($questionModel->answer == $question['my_answer']);
						$questions[$index] = $question;
					}
					
					$questionBlocks[] = array(
						'id' => $questionBlockModel->question_block_id,
						'name' => $questionBlockModel->name,
						'description' => $questionBlockModel->description,
						'questions' => $questions,
					);
				}	
			}
			
			$this->render('analysis', array(
				'pageName' => '查看解析',
				'analysisName' =>  $examPaperModel->name,
				'questionBlocks' => $questionBlocks,
			));
		}			
	}
	
	private function getQuestions($examPaperId, $questionBlockId, $examPaperInstanceId = -1, $withCorrectAnswer = false, $withAnalysis = false) {
		$questions = array();
		$criteria = new CDbCriteria();
		$criteria->addCondition('exam_paper_id = ' . $examPaperId);  
		$criteria->addCondition('question_block_id = ' . $questionBlockId);  
		$criteria->order = 'sequence asc';
		$examPaperQuestionModels = ExamPaperQuestionModel::model()->findAll($criteria);
		if ($examPaperQuestionModels != null) {
			$index = 0;
			$userId = Yii::app()->user->id;
			foreach ($examPaperQuestionModels as $examPaperQuestion) {
				$questionId = $examPaperQuestion->question_id;
				$questionModel = QuestionModel::model()->findByPk($questionId);	
				if ($questionModel != null) {
					$answer = array();
					if ($withCorrectAnswer) {
						if ($questionModel->answer != null) {
							$answer = explode("|", $questionModel->answer);
						}
					} else if ($examPaperInstanceId != -1) {
						$myAnswerRawStr = $this->getQuestionAnswer($questionId, $examPaperInstanceId);
						if ($myAnswerRawStr != null) {
							$answer = explode("|", $myAnswerRawStr);
						}
					}
					
					$question = $this->getQuestionDetailFromModel($questionModel, $answer, $withAnalysis);
					$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionId);
					$questions[$index] = $question;
					$index++;
				}
			}
		}
		return $questions;
	}
	
	private function getQuestionAnswer($questionId, $examPaperInstanceId) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('exam_paper_instance_id = ' . $examPaperInstanceId);  
		$criteria->addCondition('question_id = ' . $questionId);
		$questionInstanceModels = QuestionInstanceModel::model()->findAll($criteria);
		if ($questionInstanceModels != null && count($questionInstanceModels) > 0) {
			return $questionInstanceModels[0]->myanswer;
		}
		return null;
	}
	
	private function getRealExamPaperPractiseTimes($examPaperId, $userId) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('user_id = ' . $userId);  
		$criteria->addCondition('exam_paper_id = ' . $examPaperId); 
		return ExamPaperInstanceModel::model()->count($criteria);
	}
	
}
