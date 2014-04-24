<?php

class SubjectController extends AdminController
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($exam_bank_id = null)
	{
		$res = array();
		
		$model=new SubjectModel;
		
		$examBankModel = null;
		if ($exam_bank_id && !ExamBankModel::model()->exists('exam_bank_id=:id', array(':id'=>$exam_bank_id))) {
			throw new Exception("ID为'{$exam_bank_id}'的题库不存在");
		}
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['SubjectModel']))
		{
			$model->attributes=$_POST['SubjectModel'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$model->exam_bank_id = $examBankModel->exam_bank_id;
		
		$res['model'] = $model;
		$res['examBanks'] = ExamBankModel::model()->findAll();
		$res['examPoints'] = ExamPointModel::model()->top()->findAll();
		$this->render('create', $res);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SubjectModel']))
		{
			$model->attributes=$_POST['SubjectModel'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SubjectModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SubjectModel']))
			$model->attributes=$_GET['SubjectModel'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SubjectModel the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SubjectModel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
