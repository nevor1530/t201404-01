<?php

class UserController extends AdminController
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new UserModel;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserModel']))
		{
			$model->attributes=$_POST['UserModel'];
			$model->password = md5($_POST['UserModel']['password']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}

		$model->password = null;
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

		if(isset($_POST['UserModel']))
		{
			// ignore the password if it is empty
			if (empty($_POST['UserModel']['password'])){
				unset($_POST['UserModel']['password']);
			} else {
				$_POST['UserModel']['password'] = md5($_POST['UserModel']['password']);
			}
			
			$model->attributes=$_POST['UserModel'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}

		$model->password = null;
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserModel']))
			$model->attributes=$_GET['UserModel'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserModel the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UserModel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
