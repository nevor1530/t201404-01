<?php
$breadcrumbs = array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
);

if ($material_id != null && $material_id != 0) {
	$breadcrumbs['编辑材料题'] = array('/admin/question/viewMaterialQuestion', 'subject_id'=>$subject_id, 'material_id' => $material_id);
}

$breadcrumbs[] = '更新选择题';
$this->breadcrumbs = $breadcrumbs;
?>

<?php 
echo $this->renderPartial('_choice_question_form', array(
	'choiceQuestionForm'=> $choiceQuestionForm,
	'examPaperListData' => $examPaperListData,
	'choiceQuestionTypes' => $choiceQuestionTypes,
	'examPointListData' => $examPointListData,
	'questionAnswerOptions' => $questionAnswerOptions,
	'isNewRecord' => false
)); ?>