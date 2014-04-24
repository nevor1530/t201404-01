<?php
$this->breadcrumbs=array(
	'Exam Point Models'=>array('index'),
	$model->name=>array('view','id'=>$model->exam_point_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ExamPointModel','url'=>array('index')),
	array('label'=>'Create ExamPointModel','url'=>array('create')),
	array('label'=>'View ExamPointModel','url'=>array('view','id'=>$model->exam_point_id)),
	array('label'=>'Manage ExamPointModel','url'=>array('admin')),
);
?>

<h1>Update ExamPointModel <?php echo $model->exam_point_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>