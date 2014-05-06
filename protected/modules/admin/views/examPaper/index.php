<?php
$this->breadcrumbs=array(
	'Exam Paper Models',
);

$this->menu=array(
	array('label'=>'Create ExamPaperModel','url'=>array('create')),
	array('label'=>'Manage ExamPaperModel','url'=>array('admin')),
);
?>

<h1>Exam Paper Models</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
