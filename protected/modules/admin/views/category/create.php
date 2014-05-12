<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷分类'=>array('/admin/category/index', 'subject_id'=>$subjectModel->subject_id),
	'创建分类'
);

$this->menu=array(
);
?>

<h2>创建分类</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>