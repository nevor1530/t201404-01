<?php
/* @var $this ExamBankController */
/* @var $model ExamBankModel */

$this->breadcrumbs=array(
);

$this->menu=array(
	array('label'=>'创建题库', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#exam-bank-model-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'exam-bank-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'price',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
