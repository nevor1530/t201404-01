<?php
/* @var $this PayRecordController */
/* @var $model PayRecordModel */

$this->breadcrumbs=array(
	'Pay Record Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PayRecordModel', 'url'=>array('index')),
	array('label'=>'Manage PayRecordModel', 'url'=>array('admin')),
);
?>

<h1>Create PayRecordModel</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>