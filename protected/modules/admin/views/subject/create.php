<?php
/* @var $this SubjectController */
/* @var $model SubjectModel */

$this->breadcrumbs=array(
	'Subject Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SubjectModel', 'url'=>array('index')),
	array('label'=>'Manage SubjectModel', 'url'=>array('admin')),
);
?>

<h1>Create SubjectModel</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>