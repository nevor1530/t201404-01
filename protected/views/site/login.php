<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
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
			<label class="control-label"><?php echo $form->labelEx($model,'username'); ?></label>
			<div class="controls">
				<input class="username-input" type="text" id="LoginForm_username" name="LoginForm[username]" placeholder="请输入邮箱">
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><?php echo $form->labelEx($model,'password'); ?></label>
			<div class="controls">
				<input class="password-input" type="text" id="LoginForm_password" name="LoginForm[password]" placeholder="请输入6个以上字符">
			</div>
		</div>
	
		<div class="control-group">
			<input class="login-btn" type="submit" value="登录">
			<input type="checkbox">
			<span class="auto-login-text">下次自动登录</span>
		</div>
		
		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>
