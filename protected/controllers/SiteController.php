<?php

class SiteController extends Controller
{
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
			array('allow', // allow authenticated user to perform the following actions
				'actions'=>array('index', 'updatePassword', 'logout'),
				'users'=>array('@'),
			),
			array('allow',  
				'actions'=>array('login', 'error', 'register'),
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->redirect(array('examBank/index'));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo json_encode(array('status'=>1, 'errMsg'=>$error['message']));
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				if (Yii::app()->session['is_admin'] == true) {
					$this->redirect(array('/admin'));
				} else {
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}
		// display the login form
		$this->layout = 'main';
		$this->render('login',array('model'=>$model));
	}
	
	
	public function actionRegister() {
		$model = new RegisterForm;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='register-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['RegisterForm']))
		{
			$model->attributes=$_POST['RegisterForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->register()) 
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->layout = 'main';
		$this->render('register',array('model'=>$model));
	}
	
	public function actionUpdatePassword($result = '') {
		$model=new UpdatePasswordForm;
		$model->username = Yii::app()->user->name;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='update-password-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['UpdatePasswordForm']))
		{
			$model->attributes=$_POST['UpdatePasswordForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate()) {
				if ($model->updatePassword()) {
					$this->redirect(array('updatePassword', 'result'=> 'success'));
				} else {
					$this->redirect(array('updatePassword', 'result'=> 'fail'));
				}
			} else {
				$result = '';
			}
		}
		
		// display the login form
		$this->layout = 'main';
		$this->render('update_password',array('model'=>$model, 'result' => $result));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
}