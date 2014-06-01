<!-- 专项训练，真题模考，我的练习 单独的页面内容 -->
<ul class="subfunction-list">
	<li class="current">练习历史</li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/wrongQuestions", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的错题</a></li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/favorites", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的收藏</a></li>
</ul>

<div class="content">
	<?php foreach ($history as $item) { ?>
	<div class="paper-item">
		<div class="inline-block text-left paper-item-left">
			<a class="title"><?php echo $item['name']; ?></a>
			<div class="practice-history-info">
				练习时间：<span class="practice-time"><?php echo $item['start_time']; ?></span>
				<?php if($item['is_completed'] == 0) { ?>
				练习情况：<span class="practice-status">未完成</span>
				<?php } else {?>
				练习情况：<span class="practice-status">答对<?php echo $item['correct_question_count']; ?>道题/共<?php echo $item['total_question_count']; ?>道题</span>
				<?php } ?>
			</div>
		</div>
		<div class="inline-block paper-item-opt">
			<?php if($item['is_completed'] == 0) { ?>
			未完成<a class="btn blue-btn" href="<?php echo Yii::app()->createUrl("/examPoint/ContinuePractise", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId, 'exam_paper_instance_id' => $item['exam_paper_instance_id']))?>">继续练习</a>
			<?php } else {?>
				<a class="btn green-btn" href="#">查看解析</a>
				<a class="btn red-btn" href="#">查看报告</a>
			<?php } ?>	
		</div>
	</div>
	<?php } ?>
	
	<!-- pagination -->
	<div id="pager" style="padding-top:30px;padding-bottom:40px;">    
	<?php
	$this->widget('CLinkPager',array(    
		'header'=>'',    
		'firstPageLabel' => '首页',    
		'lastPageLabel' => '末页',    
		'prevPageLabel' => '上一页',    
		'nextPageLabel' => '下一页',    
		'pages' => $pages,    
		'maxButtonCount'=>6   
	));?>    
	</div>  
</div>
