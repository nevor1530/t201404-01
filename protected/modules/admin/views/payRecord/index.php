<?php
/* @var $this PayRecordController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pay Record Models',
);

$this->menu=array(
	array('label'=>'Create PayRecordModel', 'url'=>array('create')),
	array('label'=>'Manage PayRecordModel', 'url'=>array('admin')),
);
?>

<h1>Pay Record Models</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
