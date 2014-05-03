<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'subject-model-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">带<span class="required">*</span>为必填项目.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>
	
	<?php echo $form->hiddenField($model, 'exam_bank_id'); ?>

	<?php echo $form->textFieldRow($model,'exam_point_show_level',array('class'=>'span5')); ?>
	
	<?php echo $form->checkBoxRow($model, 'do_paper_recommendation'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '更新',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
