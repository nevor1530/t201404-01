<?php
/* @var $this SubjectController */
/* @var $model SubjectModel */

$this->breadcrumbs=array(
	$model->examBank->name=>array('/admin'),
	$model->name,
);

include($this->module->viewPath.'/common/subject_side_nav.php');

$this->menu=array(
	array('label'=>'修改课程', 'url'=>array('update', 'id'=>$model->subject_id)),
	array('label'=>'删除课程', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->subject_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>课程'<?php echo $model->name; ?>'</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'do_paper_recommendation:boolean',
		'exam_point_show_level:number',
	),
)); ?>
