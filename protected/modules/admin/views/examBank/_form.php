<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'exam-bank-model-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="help-block">带<span class="required">*</span>为必填项目</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'price',array('class'=>'span5')); ?>
	
	<?php echo $form->labelEx($model, 'icon'); echo $form->fileField($model,'icon',array('class'=>'span5'));?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '修改',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
