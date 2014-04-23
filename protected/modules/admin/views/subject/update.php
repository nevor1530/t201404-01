<?php
$this->breadcrumbs=array(
	'Subject Models'=>array('index'),
	$model->name=>array('view','id'=>$model->subject_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SubjectModel','url'=>array('index')),
	array('label'=>'Create SubjectModel','url'=>array('create')),
	array('label'=>'View SubjectModel','url'=>array('view','id'=>$model->subject_id)),
	array('label'=>'Manage SubjectModel','url'=>array('admin')),
);
?>

<h1>Update SubjectModel <?php echo $model->subject_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>