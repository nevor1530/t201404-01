<?php

class DefaultController extends AdminController
{
	public $layout='main';
	
	public function actionIndex()
	{
		$this->redirect(array('examBank/index'));
	}
}