<?php $this->beginContent('//layouts/main'); ?>
<div class="user-center">
	<div class="left-side">
		<div class="title">服务中心</div>
		<ul class="menu">
			<li class="current"><a href="#" class="hover-origen">用户信息</a></li>
			<li><a href="#">修改密码</a></li>
			<li><a href="#">会员充值</a></li>
			<li><a href="#">课程管理</a></li>
		</ul>
	</div>
	<div class="right-side">
		<?php echo $content;?>
	</div>
</div>
<?php $this->endContent(); ?>