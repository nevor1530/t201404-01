<?php
/* @var $this UserController */
/* @var $model UserModel */

$this->breadcrumbs=array(
	'User Models'=>array('index'),
	$model->name=>array('view','id'=>$model->user_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserModel', 'url'=>array('index')),
	array('label'=>'Create UserModel', 'url'=>array('create')),
	array('label'=>'View UserModel', 'url'=>array('view', 'id'=>$model->user_id)),
	array('label'=>'Manage UserModel', 'url'=>array('admin')),
);
?>

<h1>Update UserModel <?php echo $model->user_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>