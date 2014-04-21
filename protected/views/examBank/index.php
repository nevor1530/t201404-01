<?php
/* @var $this ExamBankController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Exam Bank Models',
);

$this->menu=array(
	array('label'=>'Create ExamBankModel', 'url'=>array('create')),
	array('label'=>'Manage ExamBankModel', 'url'=>array('admin')),
);
?>

<h1>Exam Bank Models</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
