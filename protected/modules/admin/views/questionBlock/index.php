<?php
$this->breadcrumbs=array(
	'Question Block Models'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List QuestionBlockModel','url'=>array('index')),
	array('label'=>'Create QuestionBlockModel','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('question-block-model-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Question Block Models</h1>

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
	'id'=>'question-block-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'question_block_id',
		'name',
		'description',
		'examp_paper_id',
		'time_length',
		'question_number',
		/*
		'score',
		'score_rule',
		'sequence',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
