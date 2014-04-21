<?php
/* @var $this PaymentController */
/* @var $model PaymentModel */

$this->breadcrumbs=array(
	'Payment Models'=>array('index'),
	$model->payment_id,
);

$this->menu=array(
	array('label'=>'List PaymentModel', 'url'=>array('index')),
	array('label'=>'Create PaymentModel', 'url'=>array('create')),
	array('label'=>'Update PaymentModel', 'url'=>array('update', 'id'=>$model->payment_id)),
	array('label'=>'Delete PaymentModel', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->payment_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PaymentModel', 'url'=>array('admin')),
);
?>

<h1>View PaymentModel #<?php echo $model->payment_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'payment_id',
		'user_id',
		'exam_bank_id',
		'expiry',
	),
)); ?>
