<?php
$this->breadcrumbs=array(
	'Exam Paper Models'=>array('index'),
	$model->name=>array('view','id'=>$model->examp_paper_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ExamPaperModel','url'=>array('index')),
	array('label'=>'Create ExamPaperModel','url'=>array('create')),
	array('label'=>'View ExamPaperModel','url'=>array('view','id'=>$model->examp_paper_id)),
	array('label'=>'Manage ExamPaperModel','url'=>array('admin')),
);
?>

<h1>Update ExamPaperModel <?php echo $model->examp_paper_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>