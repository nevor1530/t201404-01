<?php
/* @var $this PaymentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Payment Models',
);

$this->menu=array(
	array('label'=>'Create PaymentModel', 'url'=>array('create')),
	array('label'=>'Manage PaymentModel', 'url'=>array('admin')),
);
?>

<h1>Payment Models</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
