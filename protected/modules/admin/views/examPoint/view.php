<?php
$this->breadcrumbs=array(
	'Exam Point Models'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ExamPointModel','url'=>array('index')),
	array('label'=>'Create ExamPointModel','url'=>array('create')),
	array('label'=>'Update ExamPointModel','url'=>array('update','id'=>$model->exam_point_id)),
	array('label'=>'Delete ExamPointModel','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->exam_point_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ExamPointModel','url'=>array('admin')),
);
?>

<h1>View ExamPointModel #<?php echo $model->exam_point_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'exam_point_id',
		'name',
		'pid',
		'subject_id',
		'order',
	),
)); ?>
