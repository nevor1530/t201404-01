<?php

class QuestionController extends AdminController
{
	public static $questionTypes = array (	
		'1' => '单选',
		'2' => '多选',
		'3' => '不定项',
		'4' => '填空',
		'5' => '判断',
		'6' => '材料',
	);
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete, create, ajaxCreate, ajaxUpdate, visible', // we only allow deletion via POST request
		);
	}
	
	public function actionIndex($subject_id) {
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$questionModel=new QuestionModel('search');
		$questionModel->unsetAttributes();  // clear any default values
		if(isset($_GET['QuestionModel'])) {
			$questionModel->attributes=$_GET['QuestionModel'];
		}

		$this->render('index', array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'questionModel'=>$questionModel,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
		));
	}	
	
	public function actionCreateChoiceQuestion($subject_id) {
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
			
		$choiceQuestionModel=new ChoiceQuestionForm;
		$choiceQuestionTypes = array_slice(self::$questionTypes, 0, 3);
		$this->render('create_choice_question', array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'choiceQuestionModel' => $choiceQuestionModel,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'choiceQuestionTypes' => $choiceQuestionTypes,
		));
	}
	
	public function actionCreateTrueOrFalseQuestion($subject_id) {
		$trueOrFalseQuestionForm = new TrueOrFalseQuestionForm;
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		$questionAnswerOptions = array('1' => '√', '2' => 'X');
	
		$result = array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'trueOrFalseQuestionForm' => $trueOrFalseQuestionForm,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'questionAnswerOptions'=>$questionAnswerOptions,
		);
			
		if(isset($_POST['TrueOrFalseQuestionForm'])) {
			$trueOrFalseQuestionForm->attributes=$_POST['TrueOrFalseQuestionForm'];
			if ($trueOrFalseQuestionForm->validate()) {
				$MaterialModel = new MaterialModel;
				$MaterialModel->content = $trueOrFalseQuestionForm->content;
				
				if ($MaterialModel->validate() && $MaterialModel->save()) {
					$questionModel = new QuestionModel();
					$questionModel->exam_paper_id = ($trueOrFalseQuestionForm->examPaper != null) ? $trueOrFalseQuestionForm->examPaper : 0;
					$questionModel->question_type_id = 5;
					$questionModel->material_id = $MaterialModel->material_id;
					$questionModel->index = ($trueOrFalseQuestionForm->questionNumber != null) ? $trueOrFalseQuestionForm->questionNumber : 0;
					$questionModel->is_multiple = 0;
					$questionModel->answer = $trueOrFalseQuestionForm->answer;
			
					if ($questionModel->validate() && $questionModel->save()) {
						$this->redirect('', $result);
					}
				}
			}
		}
		
		
		$this->render('create_true_false_question', $result);
	}
	
	private function getExamPaperListData($subject_id) {
		$examPaperModel=ExamPaperModel::model()->findAll('subject_id=:subject_id', array(':subject_id' => $subject_id));
		$examPaperListData = CHtml::listData($examPaperModel, 'exam_paper_id', 'name');
		return $examPaperListData;
	}
	
}
