<?php
/* @var $this UserController */
/* @var $model UserModel */

$this->breadcrumbs=array(
	'User Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserModel', 'url'=>array('index')),
	array('label'=>'Manage UserModel', 'url'=>array('admin')),
);
?>

<h1>Create UserModel</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>