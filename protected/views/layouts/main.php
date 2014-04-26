<?php /* @var $this Controller */ 
	Yii::app()->bootstrap->register();  
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/index.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<div class="navbar navbar-fixed-top">
	<div class="row">
		<div class="offset2 span4 text-left">
			<img class="logo" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png"/>
		</div>
		<?php if (Yii::app()->user->isGuest) : ?>
		<div class="span4 text-right btn-container">
			<a href="<?php echo $this->createUrl('site/register') ?>" class="register-btn"><span>注册</span></a>
			<span class="divider"></span>
			<a href="<?php echo $this->createUrl('site/login') ?>" class="login-btn">登陆</a>
		</div>
		<?php else : ?>
		<div class="span4 text-right btn-container">
			<a href="" class="username"><span><?php echo Yii::app()->user->name ?></span></a>
		</div>
		<?php endif ?>
	</div>
</div>
	
<div class="container" id="page">
	
	<?php echo $content; ?>
	
	<div class="clear"></div>

	<div id="footer">
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
