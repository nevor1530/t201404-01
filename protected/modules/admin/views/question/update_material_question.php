<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
	'更新材料题',
);
?>

<?php 
echo $this->renderPartial('_material_question_form', array(
	'materialQuestionForm'=>$materialQuestionForm,
	'examPaperListData' => $examPaperListData,
	'isNewRecord' => false
)); ?>
