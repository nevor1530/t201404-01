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

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php  echo $form->dropDownListRow($trueOrFalseQuestionForm, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'暂不指定试卷')); ?>
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
	'label'=>$isNewRecord ? '创建' : '修改',
)); ?>
</div>
<?php $this->endWidget(); ?>
