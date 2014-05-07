<?php

class ExamPointController extends AdminController
{
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete, create, ajaxCreate, ajaxUpdate, visible', // we only allow deletion via POST request
		);
	}
	
	public function actionIndex($subject_id)
	{
		$res = array();
		
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null)
			throw new CHttpException(404,'The requested page does not exist.');

		$res['subjectModel']=$subjectModel;
		$res['data'] = $this->genTreeData(ExamPointModel::model()->top()->findAll());
		$res['examPointModel'] = new ExamPointModel();	// 让index页面加载ajax form需要的js文件
		$res['examPointModel']->subject_id = htmlspecialchars($subject_id);
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
		$this->performAjaxValidation($model);

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
	
	public function actionAjaxUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ExamPointModel']))
		{
			$model->attributes=$_POST['ExamPointModel'];
			if($model->save()){
				
			}
		}
	}
	
	public function actionAjaxModel($id){
		$model = $this->loadModel($id);
		if ($model){
			$result = $model->attributes;
			$data = array('status'=>0, 'data'=>$result);
		} else {
			$data = array('status'=>1, 'errMsg'=>'数据不存在');
		}
		echo json_encode($data);
		Yii::app()->end();
	}

	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		if ($model){
			$this->deleteExamPoints(array($model));
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionVisible(){
		$id = $_POST['id'];
		$value = $_POST['value'];
		$model = $this->loadModel($id);
		$value = $value ? 1 : 0;
		$model->visible = $value;
		$model->save();
	}
	
	public function actionMove($id, $direction){
		$model = $this->loadModel($id);
		$criteria = new CDbCriteria();
		$criteria->limit = 1;
		$criteria->addCondition('pid='.$model->pid);
		if ($direction === 'up') {
			$criteria->order = '`order` desc';
			$criteria->addCondition('`order`<'.$model->order);
			$anotherModel = ExamPointModel::model()->find($criteria);
			if (!$anotherModel){
				echo json_encode(array('status'=>1, 'errMsg'=>'当前位置已是首位，不能再上移'));
				Yii::app()->end();
			} else {
				$model->order--;
				$anotherModel->order++;
				$model->save();
				$anotherModel->save();
			}
		} elseif ($direction === 'down') {
			$criteria->order = '`order` asc';
			$criteria->addCondition('`order`>'.$model->order);
			$anotherModel = ExamPointModel::model()->find($criteria);
			if (!$anotherModel){
				echo json_encode(array('status'=>1, 'errMsg'=>'当前位置已是末尾，不能再下移'));
				Yii::app()->end();
			} else {
				$model->order++;
				$anotherModel->order--;
				$model->save();
				$anotherModel->save();
			}
		}
		echo json_encode(array('status'=>0));
		Yii::app()->end();
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
	
	private function deleteExamPoints($models){
		if (is_array($models)){
			foreach($models as $model){
				if ($model->subExamPoints){
					$this->deleteExamPoints($model->subExamPoints);
				}
				$model->delete();
			}
		}
	}
	
	private function genTreeData($models){
		$data = array();
		foreach($models as $model){
			$item = array();
			$item['text'] = $model->name;
			$item['id'] = $model->exam_point_id;
			$item['model'] = $model;
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
