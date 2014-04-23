<?php
$this->breadcrumbs=array(
	'Subject Models'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SubjectModel','url'=>array('index')),
	array('label'=>'Create SubjectModel','url'=>array('create')),
	array('label'=>'Update SubjectModel','url'=>array('update','id'=>$model->subject_id)),
	array('label'=>'Delete SubjectModel','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->subject_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SubjectModel','url'=>array('admin')),
);
?>

<h1>View SubjectModel #<?php echo $model->subject_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'subject_id',
		'exam_bank_id',
		'exam_point_id',
		'name',
	),
)); ?>
