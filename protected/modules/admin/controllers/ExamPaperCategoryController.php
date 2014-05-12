<?php

class ExamPaperCategoryController extends AdminController
{
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($category_id)
	{
		$categoryModel=CategoryModel::model()->findByPk($category_id);
		if($categoryModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$model=new ExamPaperCategoryModel;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		// get exam papers
		$criteria = new CDbCriteria();
		$criteria->compare('t.subject_id', $categoryModel->subject_id);
		$criteria->join = 'LEFT JOIN exam_paper_category ON exam_paper_category.exam_paper_id=t.exam_paper_id';
		$criteria->addCondition('exam_paper_category.exam_paper_category_id is NULL');
		$criteria->compare('is_real', 1);
		
		$examPaperModels = ExamPaperModel::model()->findAll($criteria);

		if(isset($_POST['ExamPaperCategoryModel']))
		{
			$model->attributes=$_POST['ExamPaperCategoryModel'];
			if($model->save())
				$this->redirect(array('index','category_id'=>$category_id));
		}

		$model->category_id = $category_id;
		$this->render('create',array(
			'model'=>$model,
			'subjectModel'=>$categoryModel->subject,
			'categoryModel'=>$categoryModel,
			'examPaperModels'=>$examPaperModels,
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

		if(isset($_POST['ExamPaperCategoryModel']))
		{
			$model->attributes=$_POST['ExamPaperCategoryModel'];
			if($model->save())
				$this->redirect(array('index','subject_id'=>$model->subject_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'subjectModel'=>$model->subject,
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
	public function actionIndex($category_id)
	{
		$categoryModel=CategoryModel::model()->findByPk($category_id);
		if($categoryModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$subjectModel = $categoryModel->subject;
			
		$model=new ExamPaperCategoryModel('search');
		$model->unsetAttributes();  // clear any default values
		$model->category_id = $category_id;
		if(isset($_GET['ExamPaperCategoryModel']))
			$model->attributes=$_GET['ExamPaperCategoryModel'];

		$this->render('index',array(
			'model'=>$model,
			'subjectModel'=>$subjectModel,
			'categoryModel'=>$categoryModel,
		));
	}
	
	public function actionMove($id, $direction){
		$model = $this->loadModel($id);
		return parent::_actionMove($model, $direction, array('category_id='.$model->category_id));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ExamPaperCategoryModel::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='exam-paper-category-model-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
