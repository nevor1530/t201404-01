<?php
$this->breadcrumbs=array(
		$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'真题推荐'=>array('/admin/paperRecommendation/index', 'subject_id'=>$subjectModel->subject_id),
	'更新真题推荐',
);

$this->menu=array(
);
?>

<h2>更新推荐 <?php echo $model->paper_recommendation_id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model, 'examPaperModels'=>$examPaperModels)); ?>