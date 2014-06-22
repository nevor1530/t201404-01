<?php
$this->pageTitle=Yii::app()->name . ' - 注册';
/* @var $this RegisterFormController */
/* @var $model RegisterForm */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

<div class="register">
	<div class="title-group">
		<span class="user-icon"></span>
		<span class="title">注册成为学海题库会员</span>
	</div>	

	<div class="register-form form-horizontal">
		<div class="control-group">
			<?php echo $form->labelEx($model,'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<input class="username-input" type="text" id="RegisterForm_username" name="RegisterForm[username]" value="<?php if(isset($model['username'])) echo $model['username']; ?>" placeholder="请输入邮箱">
			</div>
		</div>
	
		<div class="control-group">
			<?php echo $form->labelEx($model,'password', array('class'=>'control-label')); ?>
			<div class="controls">
				<input class="password-input" type="password" id="RegisterForm_password" name="RegisterForm[password]" placeholder="请输入6个以上字符">
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $form->labelEx($model,'confirm', array('class'=>'control-label')); ?>
			<div class="controls">
				<input class="confirm-input" type="password" id="RegisterForm_confirm" name="RegisterForm[confirm]" placeholder="请输入6个以上字符">
			</div>
		</div>
	
		<?php if ($model->hasErrors('username') || $model->hasErrors('password') || $model->hasErrors('confirm')): ?>
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
			<input class="register-btn" type="submit" value="注册">
			<input type="checkbox" checked>
			<span class="policy-text">已阅读并同意使用条款和隐私策略</span>
		</div>
	
		<div class="control-group">
			<input class="login-btn" type="button" onclick="location.href='<?php echo Yii::app()->createUrl('site/login');?>'" value="已有账号？立即登录>>">
		</div>
	</div> <!-- form -->
</div>

<?php $this->endWidget(); ?>