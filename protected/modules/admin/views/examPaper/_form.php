<?php 
Yii::app()->umeditor->register();

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'exam-paper-model-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">带<span class="required">*</span>为必填项目</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'subject_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'short_name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'score',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'recommendation',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'category_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'time_length',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
