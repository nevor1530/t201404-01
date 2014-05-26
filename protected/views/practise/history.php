<!-- 专项训练，真题模考，我的练习 单独的页面内容 -->
<ul class="subfunction-list">
	<li class="current">练习历史</li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/wrongQuestions", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的错题</a></li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/favorites", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的收藏</a></li>
</ul>

<div class="content">
</div>
