<?php

class SubjectController extends AdminController
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($exam_bank_id)
	{
		$res = array();
		
		$model=new SubjectModel;
		
		$examBankModel = ExamBankModel::model()->findByPk($exam_bank_id);
		if (!$examBankModel) {
			throw new Exception("ID为'{$exam_bank_id}'的题库不存在");
		}
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['SubjectModel']))
		{
			$model->attributes=$_POST['SubjectModel'];
			if($model->save()){
				NavUtil::navChanged();
				$this->redirect(array('view', 'id'=>$model->primaryKey));
			}
		}

		$model->exam_bank_id = $exam_bank_id;
		
		$res['model'] = $model;
		$res['examBankModel'] = $examBankModel;
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
			if($model->save()){
				NavUtil::navChanged();				
				$this->redirect(array('view', 'id'=>$model->primaryKey));
			}
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		NavUtil::navChanged();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$this->render('view',array(
			'subjectModel'=>$model,
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
