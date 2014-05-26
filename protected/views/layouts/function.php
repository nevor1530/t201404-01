<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
    <span class="exam-bank-btn"><?php echo $this->examBankName; ?></span>
    <ul class="subject-list">
    	<?php foreach ($this->subjects as $subject) { 
    		$subjectName = $subject['name'];
    		$style = $subject['is_current'] ? "current" : "";
    	?>
    		<li class="<?php echo $style;?>"><a href="<?php echo Yii::app()->createUrl("/examPoint/index", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$subject['id']))?>"><?php echo $subjectName; ?></a></li>
    	<?php } ?>
    </ul>
    
    <ul class="function-list">
    	<li class="<?php echo $this->curTab == PRACTISE_TAB ? "current" : "" ?>"><a href="#">专项训练</a></li>
        <li class="<?php echo $this->curTab == REAL_EXAM_PAPER_TAB ? "current" : "" ?>"><a href="#">真题模考</a></li>
        <li class="<?php echo $this->curTab == PRACTISE_HISTORY_TAB ? "current" : "" ?>"><a href="#">我的练习</a></li>
    </ul>
    
	<?php echo $content; ?>
	
    <!-- end of 专项训练，真题模考，我的练习 单独的页面内容 -->
<?php $this->endContent(); ?>
