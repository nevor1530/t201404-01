<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	$examPaperModel->name=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	'模块管理'=>array('/admin/questionBlock/index', 'exam_paper_id'=>$examPaperModel->primaryKey),
	'更新模块'
);

$this->menu=array(
);
?>

<h2>试卷 <?=$examPaperModel->name ?> 更新模块 <?=$model->name?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>