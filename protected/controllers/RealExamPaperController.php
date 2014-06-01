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
				'actions'=>array('recommendation', 'list', 'practise'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionRecommendation($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		
		$criteria = new CDbCriteria();
		$criteria->addCondition('subject_id = ' . $subject_id);  
		$criteria->addCondition('is_real = 1');  
		$count = ExamPaperModel::model()->count($criteria);    
		
		$pager = new CPagination($count);    
		$pager->pageSize = 2;             
		$pager->applyLimit($criteria);  
		
		$examPaperModels = ExamPaperModel::model()->findAll($criteria);
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
		
		$this->render('recommendation',array(
			'pages'=>$pager,
			'realExamPapers' => $realExamPapers
		));
	}
	
	public function actionList($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		$this->render('list',array());
	}
	
	public function actionPractise($exam_bank_id, $subject_id, $exam_paper_id, $question_block_id = -1) {
		$this->initial($exam_bank_id, $subject_id);
		
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
				if ($question_block_id == -1 && $i == 0) {
					$question_block_id = $questionBlockModel->question_block_id;
				}
				
				$questionBlocks[] = array(
					'id' => $questionBlockModel->question_block_id,
					'name' => $questionBlockModel->name,
					'description' => $questionBlockModel->description,
					'is_current' => ($questionBlockModel->question_block_id == $question_block_id),
				);
			}	
		}
		
		$questions = array();
		if ($question_block_id != -1) {
			$criteria = new CDbCriteria();
			$criteria->addCondition('exam_paper_id = ' . $exam_paper_id);  
			$criteria->addCondition('question_block_id = ' . $question_block_id);  
			$criteria->order = 'sequence asc';
			$examPaperQuestionModels = ExamPaperQuestionModel::model()->findAll($criteria);
			if ($examPaperQuestionModels != null) {
				$index = 0;
				foreach ($examPaperQuestionModels as $examPaperQuestion) {
					$questionId = $examPaperQuestion->question_id;
					$questionModel = QuestionModel::model()->findByPk($questionId);	
					if ($questionModel != null) {
						$questions[$index]['questionId'] = $questionId;
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
		}
		
		//header("Content-type: text/html; charset=utf8"); 
		//print_r($questionBlocks);exit();
			
		$this->render('practise',array(
			'paperName' => $examPaperModel->name,
			'questionBlocks' => $questionBlocks,
			'questions' => $questions
		));
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
