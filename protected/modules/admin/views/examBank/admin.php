<?php
$this->breadcrumbs=array(
);

$this->menu=array(
	array('label'=>'创建题库','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('exam-bank-model-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>题库管理</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-bank-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'exam_bank_id',
		'name',
		'price',
		//'subjects',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {add_subject} {delete}',
			'buttons'=>array(
				'add_subject' => array(
					'label'=>'增加课程',
					'url'=>'abc',
					'icon'=>'plus',
				),
			),
		),
	),
)); ?>
