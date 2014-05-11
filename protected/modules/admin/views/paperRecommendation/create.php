<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'真题推荐'=>array('/admin/paperRecommendation/index', 'subject_id'=>$subjectModel->subject_id),
	'创建真题推荐',
);

$this->menu=array(
);
?>

<h2>推荐真题试卷</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'examPaperModels'=>$examPaperModels)); ?>