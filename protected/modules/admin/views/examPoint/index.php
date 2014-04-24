<?php
$this->breadcrumbs=array(
	'Exam Point Models',
);

$this->menu=array(
	array('label'=>'Create ExamPointModel','url'=>array('create')),
	array('label'=>'Manage ExamPointModel','url'=>array('admin')),
);
?>

<h1>Exam Point Models</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
