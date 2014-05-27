<?php $this->beginContent('//layouts/main'); ?>
<div class="expire-tip">
	<span class="expire-tip-content">
        您的帐号将于<span class="expire-tip-time">2011年05月01日</span>过期，请提前续费
        <a class="expire-tip-btn">立即续费</a>	
    </span>
</div>
<div class="header-divider"></div>
<div id="main">
	<span class="exam-bank-btn"><?php echo $this->examBankName; ?></span>
	<ul class="subject-list">
		<?php foreach ($this->subjects as $subject) { 
			$subjectName = $subject['name'];
			$style = $subject['is_current'] ? "current" : "";
		?>
			<li class="<?php echo $style;?>">
				<a href="<?php echo Yii::app()->createUrl("/examPoint/index", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$subject['id']))?>">
					<?php echo $subjectName; ?>
				</a>
			</li>
		<?php } ?>
	</ul>
	
	<ul class="function-list">
		<li class="<?php echo $this->curTab == Constants::$EXAM_POINT_TAB ? "current" : "" ?>">
			<a href="<?php echo Yii::app()->createUrl("/examPoint/index", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$subject['id']))?>">专项训练</a>
	    <li class="<?php echo $this->curTab == Constants::$REAL_EXAM_PAPER_TAB ? "current" : "" ?>"><a href="#">真题模考</a></li>
	    <li class="<?php echo $this->curTab == Constants::$PRACTISE_TAB ? "current" : "" ?>">
	    	<a href="<?php echo Yii::app()->createUrl("/practise/history", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$subject['id']))?>">我的练习</a>
	    </li>
	</ul>

	<?php echo $content; ?>
</div>
    <!-- end of 专项训练，真题模考，我的练习 单独的页面内容 -->
<?php $this->endContent(); ?>
