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
		array('name'=>'exam_bank', 'value'=>'$data->examBank->name'),
		array('name'=>'exam_point', 'value'=>'$data->examPoint ? $data->examPoint->name : "<无>"'),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
			'header'=>'操作',
		),
	),
)); ?>
