<?php
class UMeditorImageController extends CController{
	public function actionUpload(){
		include(Yii::getPathOfAlias('umeditor.php').'/imageUp.php');
	}
}