<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'question-block-model-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">带<span class="required">*</span>为必填项目</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>40)); ?>

	<?php echo $form->textAreaRow($model,'description',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->hiddenField($model,'exam_paper_id'); ?>

	<?php echo $form->textFieldRow($model,'time_length',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'question_number',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'score',array('class'=>'span5')); ?>
	
	<?php echo $form->dropDownListRow($model, 'score_rule', QuestionBlockModel::$SCORE_RULE_MAP, array('class'=>'span5'));?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '更新',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
