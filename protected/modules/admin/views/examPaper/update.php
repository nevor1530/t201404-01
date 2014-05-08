<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷管理'=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	'更新试卷'
);

$this->menu=array(
);
?>

<h1>更新试卷  <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>