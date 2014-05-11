<?php
$breadcrumbs =array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
);

if ($material_id != null && $material_id != 0) {
	$breadcrumbs['编辑材料题'] = array('/admin/question/viewMaterialQuestion', 'subject_id'=>$subject_id, 'material_id' => $material_id);
}

$breadcrumbs[] = '添加判断题';
$this->breadcrumbs = $breadcrumbs;
?>

<?php
	Yii::app()->umeditor->register();
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'true-false-question-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); 
?>

<div class="wide form">
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($trueOrFalseQuestionForm, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'暂不指定试卷')); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($trueOrFalseQuestionForm, 'questionNumber'); ?>
		<?php echo $form->textField($trueOrFalseQuestionForm, 'questionNumber'); ?>
		<?php echo $form->error($trueOrFalseQuestionForm, 'questionNumber'); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($trueOrFalseQuestionForm, 'content'); ?>
		<?php $this->widget('umeditor.widgets.UMeditorField', array(
			'model'=>$trueOrFalseQuestionForm,
			'name'=>'content',
			'width' => '800px',
			'height' => '150px'
		)); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->radioButtonListInlineRow($trueOrFalseQuestionForm, 'answer', $questionAnswerOptions); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($trueOrFalseQuestionForm, 'examPoints', $examPointListData, array('class'=>'span5','multiple'=>true)); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($trueOrFalseQuestionForm, 'analysis'); ?>
		<?php $this->widget('umeditor.widgets.UMeditorField', array(
			'model'=>$trueOrFalseQuestionForm,
			'name'=>'analysis',
			'width' => '800px',
			'height' => '150px'
		)); ?>
	</div>
	
	<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'提交'
	)); ?>
	</div>
	
<?php $this->endWidget(); ?>
</div><!-- search-form -->
