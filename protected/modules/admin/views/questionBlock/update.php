<?php
$this->breadcrumbs=array(
	'Question Block Models'=>array('index'),
	$model->name=>array('view','id'=>$model->question_block_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List QuestionBlockModel','url'=>array('index')),
	array('label'=>'Create QuestionBlockModel','url'=>array('create')),
	array('label'=>'View QuestionBlockModel','url'=>array('view','id'=>$model->question_block_id)),
	array('label'=>'Manage QuestionBlockModel','url'=>array('admin')),
);
?>

<h1>Update QuestionBlockModel <?php echo $model->question_block_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>