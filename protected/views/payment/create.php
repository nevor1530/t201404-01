<?php
/* @var $this PaymentController */
/* @var $model PaymentModel */

$this->breadcrumbs=array(
	'Payment Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PaymentModel', 'url'=>array('index')),
	array('label'=>'Manage PaymentModel', 'url'=>array('admin')),
);
?>

<h1>Create PaymentModel</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>