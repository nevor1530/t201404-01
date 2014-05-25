<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>学海题库</title>
<script src="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/my_practice.js" type="text/javascript"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/common.css" rel="stylesheet" type="text/css"/>
</head>

<body>
	<div class="header-bg"></div>
    <div class="expire-tip">
    	<span class="expire-tip-content">
            您的帐号将于<span class="expire-tip-time">2011年05月01日</span>过期，请提前续费
            <a class="expire-tip-btn">立即续费</a>	
        </span>
    </div>
    <div class="container"> 
    	<!-- 顶部蓝底部分-->
    	<div id="header">
    		<a class="logo"></a>

			<span class="profile profile-user"><a href="#">注册</a> | <a href="#">登录</a></span>
            <span class="profile">
            	<div class="profile-user">
                    <span class="profile-avatar"></span>
                    <a href="#">zhixingzeng@gmail.com</a>
                    <span class="profile-triangle"></span>
                </div>
                <ul>
                	<li><a href="#">给当前题库续费</a></li>
                    <li><a href="#">给当前题库续费</a></li>
                    <li><a href="#">给当前题库续费</a></li>
                    <li><a href="#">给当前题库续费</a></li>
                    <li><a href="#">给当前题库续费</a></li>
                </ul>
            </span>
        </div>
        <!-- end of 顶部蓝底部分 -->
		<div class="header-divider"></div>
        <!-- 页面的内容 -->
        <div id="main">
           	<?php echo $content; ?>
        </div>
        <!-- end of 页面的内容 -->
        
        <div id="footer">
        </div>
    </div>	
</body>
</html>
