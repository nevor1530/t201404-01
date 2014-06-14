<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - 修改密码';
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'update-password-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
		
<div class="updatePassword">
	<div class="title-group">
		<span class="user-icon"></span>
		<span class="title">修改密码</span>
	</div>	
	
	<div class="update-password-form form-horizontal">
		<div class="control-group">
			<label class="control-label"><?php echo $form->labelEx($model,'username'); ?></label>
			<div class="controls">
				<label class="control-label"><?php echo 'luojunwu1988@163.com'; ?></label>
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><?php echo $form->labelEx($model,'password'); ?></label>
			<div class="controls">
				<input class="password-input" type="password" id="UpdatePasswordForm_password" name="UpdatePasswordForm[password]" placeholder="请输入6个以上字符">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php echo $form->labelEx($model,'confirm'); ?></label>
			<div class="controls">
				<input class="confirm-input" type="password" id="UpdatePasswordForm_confirm" name="UpdatePasswordForm[confirm]" placeholder="请输入6个以上字符">
			</div>
		</div>
	
		<div class="control-group">
			<input class="confirm-btn" type="submit" value="确认提交">
		</div>
		
		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>
