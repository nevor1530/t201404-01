<?php
$this->breadcrumbs=array(
	'Exam Paper Models'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ExamPaperModel','url'=>array('index')),
	array('label'=>'Create ExamPaperModel','url'=>array('create')),
	array('label'=>'Update ExamPaperModel','url'=>array('update','id'=>$model->examp_paper_id)),
	array('label'=>'Delete ExamPaperModel','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->examp_paper_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ExamPaperModel','url'=>array('admin')),
);
?>

<h1>View ExamPaperModel #<?php echo $model->examp_paper_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'examp_paper_id',
		'subject_id',
		'name',
		'short_name',
		'score',
		'recommendation',
		'category_id',
		'time_length',
		'order',
	),
)); ?>
