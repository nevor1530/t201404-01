<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷分类'=>array('/admin/category/index', 'subject_id'=>$subjectModel->subject_id),
	$categoryModel->name=>array('/admin/examPaperCategory/index', 'category_id'=>$categoryModel->category_id),
	'填加试卷'
);

$this->menu=array(
);
?>

<h1>分类 <?php echo $categoryModel->name; ?> 填加试卷</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'examPaperModels'=>$examPaperModels)); ?>