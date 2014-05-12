<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'exam-paper-category-model-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">带<span class="required">*</span>为必填项目</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'category_id'); ?>

	<?php echo $form->dropDownListRow($model,'exam_paper_id', CHtml::listData($examPaperModels, 'exam_paper_id', 'name'), array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '更新',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
