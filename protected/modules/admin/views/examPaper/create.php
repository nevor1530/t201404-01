<?php
$this->breadcrumbs=array(
	'Exam Paper Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ExamPaperModel','url'=>array('index')),
	array('label'=>'Manage ExamPaperModel','url'=>array('admin')),
);
?>

<h1>Create ExamPaperModel</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>