<?php

class QuestionBlockController extends AdminController
{
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

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
	public function actionCreate($exam_paper_id)
	{
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if (!$examPaperModel){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$model=new QuestionBlockModel;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['QuestionBlockModel']))
		{
			$model->attributes=$_POST['QuestionBlockModel'];
			if($model->save())
				$this->redirect(array('index','exam_paper_id'=>$exam_paper_id));
		}
		
		$model->exam_paper_id = $exam_paper_id;
		
		$this->render('create',array(
			'model'=>$model,
			'examPaperModel'=>$examPaperModel,
			'subjectModel'=>$examPaperModel->subject,
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

		if(isset($_POST['QuestionBlockModel']))
		{
			$model->attributes=$_POST['QuestionBlockModel'];
			if($model->save())
				$this->redirect(array('index','exam_paper_id'=>$model->exam_paper_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'examPaperModel'=>$model->examPaper,
			'subjectModel'=>$model->examPaper->subject,
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
	public function actionIndex($exam_paper_id)
	{
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if (!$examPaperModel){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$model=new QuestionBlockModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['QuestionBlockModel']))
			$model->attributes=$_GET['QuestionBlockModel'];

		$this->render('index',array(
			'model'=>$model,
			'examPaperModel'=>$examPaperModel,
			'subjectModel'=>$examPaperModel->subject,
		));
	}
	
	public function actionMove($id, $direction){
		$model = $this->loadModel($id);
		$criteria = new CDbCriteria();
		$criteria->limit = 1;
		$criteria->addCondition('exam_paper_id='.$model->exam_paper_id);
		if ($direction === 'up') {
			$criteria->order = 'sequence desc';
			$criteria->addCondition('sequence<'.$model->sequence);
			$anotherModel = QuestionBlockModel::model()->find($criteria);
			if (!$anotherModel){
				echo json_encode(array('status'=>1, 'errMsg'=>'当前位置已是首位，不能再上移'));
				Yii::app()->end();
			} else {
				$model->sequence--;
				$anotherModel->sequence++;
				$model->save();
				$anotherModel->save();
			}
		} elseif ($direction === 'down') {
			$criteria->order = 'sequence asc';
			$criteria->addCondition('sequence>'.$model->sequence);
			$anotherModel = QuestionBlockModel::model()->find($criteria);
			if (!$anotherModel){
				echo json_encode(array('status'=>1, 'errMsg'=>'当前位置已是末尾，不能再下移'));
				Yii::app()->end();
			} else {
				$model->sequence++;
				$anotherModel->sequence--;
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
		$model=QuestionBlockModel::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='question-block-model-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
