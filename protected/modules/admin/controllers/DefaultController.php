<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
		$model=new ExamBankModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExamBankModel']))
			$model->attributes=$_GET['ExamBankModel'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
}