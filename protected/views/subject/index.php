<?php
/* @var $this SubjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Subject Models',
);

$this->menu=array(
	array('label'=>'Create SubjectModel', 'url'=>array('create')),
	array('label'=>'Manage SubjectModel', 'url'=>array('admin')),
);
?>

<h1>Subject Models</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
