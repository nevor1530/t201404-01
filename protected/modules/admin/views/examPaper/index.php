<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷管理'
);

include($this->module->viewPath.'/common/subject_side_nav.php');

$this->menu=array(
	array('label'=>'创建试卷','url'=>array('create', 'subject_id'=>$subjectModel->primaryKey)),
);
?>

<h1>试卷管理</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-paper-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'publish_time',
		array(
			'name'=>'status',
			'filter'=>array(0=>'未录完', 1=>'未发布', 2=>'已发布'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
