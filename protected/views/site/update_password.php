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

<style type="text/css">

</style>

<div class="updatePassword">
	<div class="title-group">
		<span class="user-icon"></span>
		<span class="title">修改密码</span>
	</div>	
	
	<div class="update-password-form form-horizontal">
		<?php if (isset($result) && strlen($result) > 0): ?>
		<div id="alert-dialog" class="control-group">
			<div class="alert alert-info text-center">
				<button class="close" type="button" onclick="dismissAlertDialog()">×</button>
				<?php if ($result == 'success') :?> 更新成功 <?php endif; ?>
				<?php if ($result == 'fail') :?> 更新失败，请重试！  <?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
					
		<div class="control-group">
			<label class="control-label"><?php echo $form->labelEx($model,'username'); ?></label>
			<div class="controls">
				<input style="border:none" class="password-input" type="text" name="UpdatePasswordForm[username]" value="<?php echo $model->username;?>" readonly="true">
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
		
		<?php if ($model->hasErrors('password') || $model->hasErrors('confirm')): ?>
		<div class="control-group">
			<div class="controls">
				<div class="error">
				<?php
				$hasError = false;
				foreach($model->getErrors() as $errors) {
					foreach($errors as $error) {
						if($error!='') {
							$hasError = true;
							echo $error;
							break;
						}
					}
					if ($hasError) break;
				}
				?>
				</div> 
			</div>
		</div>
		<?php endif; ?>
		
		<div class="control-group">
			<input class="confirm-btn" type="submit" value="确认提交">
		</div>
		
		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>

<script type="text/javascript">
function dismissAlertDialog() {
	$('#alert-dialog').hide();	
}
</script>
