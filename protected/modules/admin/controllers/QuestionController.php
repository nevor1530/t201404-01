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
		$model=new QuestionModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['QuestionModel']))
			$model->attributes=$_GET['QuestionModel'];

		$this->render('index', array(
			'subject_id' => $subject_id,
			'model'=>$model,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
		));
	}	
	
	public function actionCreateChoiceQuestion($subject_id) {
		$choiceQuestionModel=new ChoiceQuestionForm;
		$choiceQuestionTypes = array_slice(self::$questionTypes, 0, 3);
		$this->render('create_choice_question', array(
			'subject_id' => $subject_id,
			'choiceQuestionModel' => $choiceQuestionModel,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'choiceQuestionTypes' => $choiceQuestionTypes,
		));
	}
	
	private function getExamPaperListData($subject_id) {
		$examPaperModel=ExamPaperModel::model()->findAll('subject_id=:subject_id', array(':subject_id' => $subject_id));
		$examPaperListData = CHtml::listData($examPaperModel, 'exam_paper_id', 'name');
		return $examPaperListData;
	}
	
	
}
