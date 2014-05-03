<?php

class ExamPointController extends AdminController
{
	public function actionIndex($subject_id)
	{
		$res = array();
		
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$res['subjectModel']=$subjectModel;
		$res['data'] = $this->genTreeData(ExamPointModel::model()->top()->findAll());
		$res['model'] = new ExamPointModel;	// 让index页面加载ajax form需要的js文件
		$this->render('index', $res);
	}	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ExamPointModel;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ExamPointModel']))
		{
			$model->attributes=$_POST['ExamPointModel'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->exam_point_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionAjaxCreate($subject_id = null)
	{
		$model=new ExamPointModel;
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ExamPointModel']))
		{
			$model->attributes=$_POST['ExamPointModel'];
			if($model->save()){
				// @TODO
			}
		}

		$model->subject_id = $subject_id;
		$this->layout = 'empty';
		$this->render('ajax_form',array(
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

		if(isset($_POST['ExamPointModel']))
		{
			$model->attributes=$_POST['ExamPointModel'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->exam_point_id));
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
		$model=new ExamPointModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExamPointModel']))
			$model->attributes=$_GET['ExamPointModel'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ExamPointModel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	private function genTreeData($models){
		$data = array();
		foreach($models as $model){
			$item = array();
			$item['text'] = $model->name;
			$item['id'] = $model->exam_point_id;
			if (!empty($model->subExamPoints)){
				$item['hasChildren'] = true;
				$item['children'] = $this->genTreeData($model->subExamPoints);
			}
			$data[] = $item;
		}
		return $data;
	}
	
		
	
	/**
	 * Performs the AJAX validation.
	 * @param ExamBankModel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exam-point-model-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
