<?php
$this->breadcrumbs=array(
	'Question Models',
);

$this->menu=array(
	array('label'=>'Create QuestionModel','url'=>array('create')),
	array('label'=>'Manage QuestionModel','url'=>array('admin')),
);
?>

<h1>Question Models</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
