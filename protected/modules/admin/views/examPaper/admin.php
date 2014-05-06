<?php
$this->breadcrumbs=array(
	'Exam Paper Models'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ExamPaperModel','url'=>array('index')),
	array('label'=>'Create ExamPaperModel','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('exam-paper-model-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Exam Paper Models</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-paper-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'exam_paper_id',
		'subject_id',
		'name',
		'short_name',
		'score',
		'recommendation',
		/*
		'category_id',
		'time_length',
		'order',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
