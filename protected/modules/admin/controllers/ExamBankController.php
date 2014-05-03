<?php

class ExamBankController extends AdminController
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ExamBankModel;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ExamBankModel']))
		{
			$model->attributes=$_POST['ExamBankModel'];
			if($model->save())
				NavUtil::navChanged();
				$this->redirect(array('/admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['ExamBankModel']))
		{
			$model->attributes=$_POST['ExamBankModel'];
			if($model->save())
				NavUtil::navChanged();
				$this->redirect(array('/admin'));
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
		$model=new ExamBankModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExamBankModel']))
			$model->attributes=$_GET['ExamBankModel'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		NavUtil::navChanged();
		parent::actionDelete($id);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ExamBankModel the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ExamBankModel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
