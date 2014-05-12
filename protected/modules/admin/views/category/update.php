<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷分类'=>array('/admin/category/index', 'subject_id'=>$subjectModel->subject_id),
	'更新分类'
);

$this->menu=array(
);
?>

<h2>更新分类  <?php echo $model->category_id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>