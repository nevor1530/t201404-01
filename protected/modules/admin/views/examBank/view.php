<?php
$this->breadcrumbs=array(
	'Exam Bank Models'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ExamBankModel','url'=>array('index')),
	array('label'=>'Create ExamBankModel','url'=>array('create')),
	array('label'=>'Update ExamBankModel','url'=>array('update','id'=>$model->exam_bank_id)),
	array('label'=>'Delete ExamBankModel','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->exam_bank_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ExamBankModel','url'=>array('admin')),
);
?>

<h1>View ExamBankModel #<?php echo $model->exam_bank_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'exam_bank_id',
		'name',
		'price',
	),
)); ?>
