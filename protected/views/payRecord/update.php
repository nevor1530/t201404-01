<?php
/* @var $this PayRecordController */
/* @var $model PayRecordModel */

$this->breadcrumbs=array(
	'Pay Record Models'=>array('index'),
	$model->payment_record_id=>array('view','id'=>$model->payment_record_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PayRecordModel', 'url'=>array('index')),
	array('label'=>'Create PayRecordModel', 'url'=>array('create')),
	array('label'=>'View PayRecordModel', 'url'=>array('view', 'id'=>$model->payment_record_id)),
	array('label'=>'Manage PayRecordModel', 'url'=>array('admin')),
);
?>

<h1>Update PayRecordModel <?php echo $model->payment_record_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>