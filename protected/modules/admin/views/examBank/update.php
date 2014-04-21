<?php
/* @var $this ExamBankController */
/* @var $model ExamBankModel */

$this->breadcrumbs=array(
	'Exam Bank Models'=>array('index'),
	$model->name=>array('view','id'=>$model->exam_bank_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ExamBankModel', 'url'=>array('index')),
	array('label'=>'Create ExamBankModel', 'url'=>array('create')),
	array('label'=>'View ExamBankModel', 'url'=>array('view', 'id'=>$model->exam_bank_id)),
	array('label'=>'Manage ExamBankModel', 'url'=>array('admin')),
);
?>

<h1>Update ExamBankModel <?php echo $model->exam_bank_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>