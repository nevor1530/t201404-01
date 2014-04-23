<?php
$this->breadcrumbs=array(
	'User Models'=>array('index'),
	$model->user_id,
);

$this->menu=array(
	array('label'=>'List UserModel','url'=>array('index')),
	array('label'=>'Create UserModel','url'=>array('create')),
	array('label'=>'Update UserModel','url'=>array('update','id'=>$model->user_id)),
	array('label'=>'Delete UserModel','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserModel','url'=>array('admin')),
);
?>

<h1>View UserModel #<?php echo $model->user_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'user_id',
		'username',
		'password',
		'creation_time',
		'is_admin',
	),
)); ?>
