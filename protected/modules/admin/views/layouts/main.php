<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo $this->module->assetsUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>NavUtil::getNav(),
        ),
    ),
)); ?>

<div style="position: fixed; right: 10px; top: 10px; z-index: 9999;">
	<?php if(Yii::app()->user->isGuest):?>
		<?php echo CHtml::link(('登录'), array('/site/login'));?>
	<?php else:?>
		<?php echo CHtml::link(('注销('.Yii::app()->user->name.')'), array('/site/logout'));?>
	<?php endif?>
</div>

<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
			 'homeLink'=>CHtml::link(Yii::t('zii','Home'),$this->createUrl('/admin/examBank/index')),
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
