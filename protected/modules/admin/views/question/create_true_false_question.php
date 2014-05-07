<?php
$this->breadcrumbs=array(
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
	'添加判断题',
);
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
		<?php  echo $form->dropDownListRow($trueOrFalseQuestionModel, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'全部')); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($trueOrFalseQuestionModel, 'questionNumber'); ?>
		<?php echo $form->textField($trueOrFalseQuestionModel, 'questionNumber'); ?>
		<?php echo $form->error($trueOrFalseQuestionModel, 'questionNumber'); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($trueOrFalseQuestionModel, 'content'); ?>
		<?php $this->widget('umeditor.widgets.UMeditorField', array(
			'model'=>$trueOrFalseQuestionModel,
			'name'=>'content',
			'width' => '800px',
			'height' => '150px'
		)); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->radioButtonListInlineRow($trueOrFalseQuestionModel, 'answer', $questionAnswerOptions); ?>
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
