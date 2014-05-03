<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'exam-point-model-form',
	'enableAjaxValidation'=>true,
	'clientOptions' => array(  
                        'validateOnSubmit' => true,  
                        'afterValidate'=>'js:function(form,data,hasError){  
					                        if(!hasError){  
					                                $.ajax({  
					                                        "type":"POST",  
					                                        "url":$("#exam-point-model-form").attr("action"),  
					                                        "data":$("#exam-point-model-form").serialize(),  
					                                        "success":function(data){
					                                        	location.reload();
					                                        },  
					                                          
													});
					                        }
					                      }', 
                    ), 
)); ?>

	<p class="help-block">带<span class="required">*</span>项目为必填.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>
	
	<?php echo $form->textAreaRow($model, 'description', array('class'=>'span5','maxlength'=>500)); ?>
	
	<?php echo $form->hiddenField($model,'pid'); ?>

	<?php echo $form->hiddenField($model,'subject_id'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? '创建' : '修改',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
