<?php
/* @var $this PayRecordController */
/* @var $model PayRecordModel */

$this->breadcrumbs=array(
	'Pay Record Models'=>array('index'),
	$model->payment_record_id,
);

$this->menu=array(
	array('label'=>'List PayRecordModel', 'url'=>array('index')),
	array('label'=>'Create PayRecordModel', 'url'=>array('create')),
	array('label'=>'Update PayRecordModel', 'url'=>array('update', 'id'=>$model->payment_record_id)),
	array('label'=>'Delete PayRecordModel', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->payment_record_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PayRecordModel', 'url'=>array('admin')),
);
?>

<h1>View PayRecordModel #<?php echo $model->payment_record_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'payment_record_id',
		'user_id',
		'exam_bank_id',
		'money',
	),
)); ?>
