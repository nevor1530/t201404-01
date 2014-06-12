<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="5fec3f05e522824e" />
<title>学海题库</title>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F489b48ede2c35cb1247a705458734155' type='text/javascript'%3E%3C/script%3E"));
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/my_practice.js" type="text/javascript"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/common.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div class="header-bg"></div>
    <div id="container" class="container"> 
    	<!-- 顶部蓝底部分-->
    	<div id="header">
    		<a class="logo"></a>
			<?php if (Yii::app()->user->isGuest) { ?>
			<span class="profile profile-user"><a href="<?php echo Yii::app()->createUrl("/site/register")?>">注册</a> | <a href="<?php echo Yii::app()->createUrl("/site/login")?>">登录</a></span>
			<?php } else {?>
            <span class="profile">
            	<div class="profile-user">
                    <span class="profile-avatar"></span>
                    <a href="#"><?php echo Yii::app()->user->name; ?> </a>
                    <span class="profile-triangle"></span>
                </div>
                <ul>
                	<?php if (isset($_REQUEST['exam_bank_id']) && $_REQUEST['exam_bank_id']):?>
                	<li><a href="<?php echo Yii::app()->createUrl('charge/index', array('exam_bank_id'=>htmlspecialchars($_REQUEST['exam_bank_id'])));?>">给当前题库续费</a></li>
                	<?php endif;?>
                	<li><a href="<?php echo Yii::app()->createUrl("/site/index")?>">选择其他题库</a></li>
                    <li><a href="<?php echo Yii::app()->createUrl("/site/updatePassword")?>">修改密码</a></li>
                    <li><a href="<?php echo Yii::app()->createUrl("/site/logout")?>">退出</a></li>
                </ul>
            </span>
            <?php } ?>
        </div>
        <!-- end of 顶部蓝底部分 -->
        <?php echo $content; ?>
        <div id="footer">
        </div>
    </div>	
</body>
</html>
