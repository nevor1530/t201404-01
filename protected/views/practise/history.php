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
			<a class="title" href="#"><?php echo $item['name']; ?></a>
			<div class="practice-history-info">
				练习时间：<span class="practice-time"><?php echo $item['start_time']; ?></span>
				练习情况：<span class="practice-status">未完成</span>
			</div>
		</div>
		<div class="inline-block paper-item-opt">未完成<a class="btn blue-btn">继续练习</a>
	</div>
	</div>
	<?php } ?>
	
	<div class="paper-item">
		<div class="inline-block text-left paper-item-left">
			<a class="title" href="#">试题名称</a>
			<div class="practice-history-info">
				练习时间：<span class="practice-time">2014-4-9 12:20</span>
				练习情况：<span class="practice-status">未完成</span>
			</div>
		</div>
		<div class="inline-block paper-item-opt">未完成<a class="btn blue-btn">继续练习</a>
		</div>
	</div>
	<div class="paper-item">
		<div class="inline-block text-left paper-item-left">
			<a class="title" href="#">试题名称</a>
			<div class="practice-history-info">
				练习时间：<span class="practice-time">2014-4-9 12:20</span>
				练习情况：<span class="practice-status">答对5道题/共12道题</span>
			</div>
		</div>
		<div class="inline-block paper-item-opt">
			<a class="btn red-btn" href="#">查看解析</a>
			<a class="btn blue-btn" href="#">查看报告</a>
		</div>
	</div>
	<!-- pagination -->
</div>
