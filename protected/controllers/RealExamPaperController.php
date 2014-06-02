<?php
class RealExamPaperController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/function';
	public $examBankId;
	public $examBankName;
	public $subjects;
	public $curSubjectId;
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
				'actions'=>array('list', 'practise', 'ajaxSubmitAnswer', 'continuePractise', 'completePractise'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionList($exam_bank_id, $subject_id, $is_recommendation = true) {
		$this->initial($exam_bank_id, $subject_id);
		
		$examPaperModels = array();
		if ($is_recommendation) {
			$criteria = new CDbCriteria();
			$criteria->addCondition('subject_id = ' . $subject_id);  
			$count = PaperRecommendationModel::model()->count($criteria);  
			
			$pager = new CPagination($count);    
			$pager->pageSize = 2;             
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
	
	public function actionPractise($exam_bank_id, $subject_id, $exam_paper_id) {
		$this->initial($exam_bank_id, $subject_id);
		
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if ($examPaperModel == null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$examPaperInstanceModel = new ExamPaperInstanceModel;
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
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'remainTime' =>  $examPaperModel->time_length,
				'paperName' => $examPaperModel->name,
				'questionBlocks' => $questionBlocks,
			));
		}  else {
			$this->redirect(array('recommendation', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
		}
	}
	
	public function actionContinuePractise($exam_bank_id, $subject_id, $exam_paper_instance_id) {
		$this->initial($exam_bank_id, $subject_id);
		
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
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'remainTime' =>  $examPaperModel->time_length - $examPaperInstanceModel->elapsed_time,
				'paperName' => $examPaperModel->name,
				'questionBlocks' => $questionBlocks,
			));
			Yii::app()->end();
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
	
	public function actionCompletePractise($exam_bank_id, $subject_id, $exam_paper_instance_id) {
		$userId = Yii::app()->user->id;
		$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($exam_paper_instance_id);
		if ($examPaperInstanceModel != null  && $examPaperInstanceModel->user_id == $userId) {
			$examPaperInstanceModel->is_completed = 1;
			$examPaperInstanceModel->save();
		}
		
		$this->redirect(array('recommendation', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
	}
	
	private function getQuestions($examPaperId, $questionBlockId, $examPaperInstanceId = -1) {
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
					$questions[$index]['questionId'] = $questionId;
					$questions[$index]['content'] = $questionModel->questionExtra->title;
					$questions[$index]['answerOptions'] = array();
					$questions[$index]['questionType'] = $questionModel->question_type;
					$questions[$index]['is_favorite'] = $this->isFavoriteQuestion($userId, $questionId);
					
					$myAnswers = array();
					if ($examPaperInstanceId != -1) {
						$myAnswerRawStr = $this->getQuestionAnswer($questionId, $examPaperInstanceId);
						if ($myAnswerRawStr != null) {
							$myAnswers = explode("|", $myAnswerRawStr);
						}
					}
					
					if ($questionModel->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $questionModel->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE) {
						$questionAnswerOptions = $questionModel->questionAnswerOptions;
						foreach ($questionAnswerOptions as $questionAnswerOption) {
							$questions[$index]['answerOptions'][] = array(
								'index' =>$questionAnswerOption->attributes['index'],
								'description' => $questionAnswerOption->attributes['description'],
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
	
	private function isFavoriteQuestion($userId, $questionId) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('user_id = ' . $userId);  
		$criteria->addCondition('question_id = ' . $questionId); 
		return (QuestionFavoritesModel::model()->count($criteria) > 0);
	}
	
	private function getRealExamPaperPractiseTimes($examPaperId, $userId) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('user_id = ' . $userId);  
		$criteria->addCondition('exam_paper_id = ' . $examPaperId); 
		return ExamPaperInstanceModel::model()->count($criteria);
	}
	
	private function initial($exam_bank_id, $subject_id) {
		$this->curTab = Constants::$REAL_EXAM_PAPER_TAB;
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
		
		if ($subject_id == 0 && count($subjects) == 0) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else if ($subject_id == 0) {
			$this->curSubjectId = $subjects[0]['id'];
		} else {
			$this->curSubjectId = $subject_id;
		}
	}
}
