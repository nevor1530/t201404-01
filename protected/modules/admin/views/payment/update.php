<?php
/* @var $this PaymentController */
/* @var $model PaymentModel */

$this->breadcrumbs=array(
	'Payment Models'=>array('index'),
	$model->payment_id=>array('view','id'=>$model->payment_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PaymentModel', 'url'=>array('index')),
	array('label'=>'Create PaymentModel', 'url'=>array('create')),
	array('label'=>'View PaymentModel', 'url'=>array('view', 'id'=>$model->payment_id)),
	array('label'=>'Manage PaymentModel', 'url'=>array('admin')),
);
?>

<h1>Update PaymentModel <?php echo $model->payment_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>