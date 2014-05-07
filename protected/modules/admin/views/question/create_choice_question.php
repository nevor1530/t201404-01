<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
	'添加选择题',
);
?>

<?php
	Yii::app()->umeditor->register();
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'choice-question-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); 
?>

<div class="wide form">
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($choiceQuestionModel, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'全部')); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($choiceQuestionModel, 'questionNumber'); ?>
		<?php echo $form->textField($choiceQuestionModel, 'questionNumber'); ?>
		<?php echo $form->error($choiceQuestionModel, 'questionNumber'); ?>
	</div>
	
	<div>
		<?php echo $form->radioButtonListInlineRow($choiceQuestionModel, 'questionType', $choiceQuestionTypes); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($choiceQuestionModel, 'content'); ?>
		<?php $this->widget('umeditor.widgets.UMeditorField', array(
			'model'=>$choiceQuestionModel,
			'name'=>'content',
			'width' => '800px',
			'height' => '150px'
		)); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($choiceQuestionModel, 'questionAnswerOptions'); ?>
		<?php $this->widget('umeditor.widgets.UMeditorField', array(
			'model'=>$choiceQuestionModel,
			'name'=>'questionAnswerOptions',
			'width' => '800px',
			'height' => '150px'
		)); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($choiceQuestionModel, 'answer'); ?>
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
