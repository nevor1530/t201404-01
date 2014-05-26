<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
    <span class="exam-bank-btn"><?php echo $this->examBankName; ?></span>
    <ul class="subject-list">
    	<?php foreach ($this->subjects as $subject) { 
    		$subjectName = $subject['name'];
    		$style = $subject['is_current'] ? "current" : "";
    	?>
    		<li class="<?php echo $style;?>"><a href="#"><?php echo $subjectName; ?></a></li>
    	<?php } ?>
    </ul>
    
    <ul class="function-list">
    	<li><a href="#">专项训练</a></li>
        <li><a href="#">真题模考</a></li>
        <li class="current"><a href="#">我的练习</a></li>
    </ul>
    
    <!-- 专项训练，真题模考，我的练习 单独的页面内容 -->
	<ul class="subfunction-list">
    	<li><a href="#">练习历史</a></li>
        <li class="current"><a href="#">我的错题</a></li>
        <li><a href="#">我的收藏</a></li>
    </ul>
    
    <div class="content">
    	<?php echo $content; ?>
    </div>
    <!-- end of 专项训练，真题模考，我的练习 单独的页面内容 -->
<?php $this->endContent(); ?>
