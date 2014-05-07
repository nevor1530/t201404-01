<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷管理'=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	'创建试卷'
);

$this->menu=array(
);
?>

<h1>创建试卷</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>