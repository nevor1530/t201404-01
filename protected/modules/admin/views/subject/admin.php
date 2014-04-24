<?php
$this->breadcrumbs=array(
	'课程管理',
);

$this->menu=array(
	array('label'=>'创建课程','url'=>array('create')),
);
?>

<h1>课程管理</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'subject-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'subject_id',
		'name',
		'exam_bank_id',
		'exam_point_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
