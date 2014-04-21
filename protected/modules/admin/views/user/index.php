<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Models',
);

$this->menu=array(
	array('label'=>'Create UserModel', 'url'=>array('create')),
	array('label'=>'Manage UserModel', 'url'=>array('admin')),
);
?>

<h1>User Models</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
