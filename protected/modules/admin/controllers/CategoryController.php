<?php

class CategoryController extends AdminController
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($subject_id)
	{
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$model=new CategoryModel;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CategoryModel']))
		{
			$model->attributes=$_POST['CategoryModel'];
			if($model->save())
				$this->redirect(array('index','subject_id'=>$subject_id));
		}

		$model->subject_id = $subject_id;
		$this->render('create',array(
			'model'=>$model,
			'subjectModel'=>$subjectModel,
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

		if(isset($_POST['CategoryModel']))
		{
			$model->attributes=$_POST['CategoryModel'];
			if($model->save())
				$this->redirect(array('index','subject_id'=>$model->subject_id));
		}

		$this->render('update',array(
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($subject_id)
	{
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$model=new CategoryModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CategoryModel']))
			$model->attributes=$_GET['CategoryModel'];

		$this->render('index',array(
			'model'=>$model,
			'subjectModel'=>$subjectModel,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CategoryModel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-model-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
