<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminController extends Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='/layouts/column2';
	
	/**
	 * 侧边导航
	 */
	public $sideNav=array();

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'expression'=>'isset(Yii::app()->session["is_admin"]) && Yii::app()->session["is_admin"]',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		return $this->actionAdmin();
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	
	/**
	 * Performs the AJAX validation.
	 * @param ExamBankModel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exam-bank-model-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function _actionMove($model, $direction, $groupConditions=array(), $sequenceField='sequence'){
		$criteria = new CDbCriteria();
		$criteria->limit = 1;
		
		$criteria->addCondition($groupConditions);
		if ($direction === 'up') {
			$criteria->order = $sequenceField.' desc';
			$criteria->addCondition($sequenceField.'<'.$model->$sequenceField);
			$anotherModel = $model->find($criteria);
			if (!$anotherModel){
				echo json_encode(array('status'=>1, 'errMsg'=>'当前位置已是首位，不能再上移'));
				Yii::app()->end();
			} else {
				$swap = $model->$sequenceField;
				$model->$sequenceField = $anotherModel->$sequenceField;
				$anotherModel->$sequenceField = $swap;
				$model->save();
				$anotherModel->save();
			}
		} elseif ($direction === 'down') {
			$criteria->order = $sequenceField.' asc';
			$criteria->addCondition($sequenceField.'>'.$model->$sequenceField);
			$anotherModel = $model->find($criteria);
			if (!$anotherModel){
				echo json_encode(array('status'=>1, 'errMsg'=>'当前位置已是末尾，不能再下移'));
				Yii::app()->end();
			} else {
				$swap = $model->$sequenceField;
				$model->$sequenceField = $anotherModel->$sequenceField;
				$anotherModel->$sequenceField = $swap;
				$model->save();
				$anotherModel->save();
			}
		}
		echo json_encode(array('status'=>0));
		Yii::app()->end();
	}
	
	protected function ajaxErrorSummary($errors){
		$content = '';
		foreach($errors as $itemErrors){
			foreach($itemErrors as $error)
			{
				if($error!='')
					$content.=$error."\n";
			}
		}
		return $content;
	}
}