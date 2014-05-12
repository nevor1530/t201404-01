<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'category-model-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">带<span class="required">*</span>为必填项目</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->hiddenField($model,'subject_id'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '更新',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
