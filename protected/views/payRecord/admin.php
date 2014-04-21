<?php
/* @var $this PayRecordController */
/* @var $model PayRecordModel */

$this->breadcrumbs=array(
	'Pay Record Models'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PayRecordModel', 'url'=>array('index')),
	array('label'=>'Create PayRecordModel', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#pay-record-model-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Pay Record Models</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'pay-record-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'payment_record_id',
		'user_id',
		'exam_bank_id',
		'money',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
