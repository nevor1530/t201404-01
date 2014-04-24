<?php
$this->breadcrumbs=array(
	'Exam Point Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ExamPointModel','url'=>array('index')),
	array('label'=>'Manage ExamPointModel','url'=>array('admin')),
);
?>

<h1>Create ExamPointModel</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>