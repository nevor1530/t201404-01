<?php
/* @var $this ExamBankController */
/* @var $model ExamBankModel */

$this->breadcrumbs=array(
	'Exam Bank Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ExamBankModel', 'url'=>array('index')),
	array('label'=>'Manage ExamBankModel', 'url'=>array('admin')),
);
?>

<h1>Create ExamBankModel</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>