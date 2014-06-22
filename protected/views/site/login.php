<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - 登录';
$this->breadcrumbs=array(
	'Login',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
		
<div class="login">
	<div class="title-group">
		<span class="user-icon"></span>
		<span class="title">学海题库欢迎您回来练习</span>
	</div>	
	
	<div class="login-form form-horizontal">
		<div class="control-group">
			<?php echo $form->labelEx($model,'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<input class="username-input" type="text" id="LoginForm_username" name="LoginForm[username]" placeholder="请输入邮箱">
			</div>
		</div>
	
		<div class="control-group">
			<?php echo $form->labelEx($model,'password', array('class'=>'control-label')); ?>
			<div class="controls">
				<input class="password-input" type="password" id="LoginForm_password" name="LoginForm[password]" placeholder="请输入6个以上字符">
			</div>
		</div>
		
		<?php if ($model->hasErrors('username') || $model->hasErrors('password')): ?>
		<div class="control-group">
			<div class="controls">
				<div class="error">用户名或者密码不正确</div> 
			</div>
		</div>
		<?php endif; ?>
	
		<div class="control-group">
			<input class="login-btn" type="submit" value="登录">
			<input type="checkbox" name="LoginForm[rememberMe]" value="1">
			<span class="auto-login-text">下次自动登录</span>
		</div>
		
		<div class="control-group">
			<input class="register-btn" type="button" onclick="location.href='<?php echo Yii::app()->createUrl('site/register');?>'" value="还没有账号？立即注册>>">
		</div>
		
		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>