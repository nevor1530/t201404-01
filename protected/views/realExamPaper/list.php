<ul class="subfunction-list">
	<li class="<?php echo $isRecommendation ? "current" : ""; ?>"><a href="<?php echo Yii::app()->createUrl("/realExamPaper/list", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId, 'is_recommendation' => true))?>">推荐真题</a></li>
    <li class="<?php echo $isRecommendation ? "" : "current"; ?>"><a href="<?php echo Yii::app()->createUrl("/realExamPaper/list", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId, 'is_recommendation' => false))?>">全部真题</a></li>
</ul>

<div class="content">
	<?php foreach ($realExamPapers as $realExamPaper) { ?>
	<div class="paper-item">
		<div class="inline-block text-left paper-item-left">
			<a class="title"><?php echo $realExamPaper['name']; ?></a>
			<div class="recommendation-info">
				推荐：
				<?php 
				if ($realExamPaper['recommendation_value'] < 0) { $realExamPaper['recommendation_value'] = 0; } 
				if ($realExamPaper['recommendation_value'] > 5) { $realExamPaper['recommendation_value'] = 5; } 
				for ($i = 0; $i < $realExamPaper['recommendation_value']; $i++) {
				?>
					<span class="selected-star"></span>
				<?php } ?>
				<?php for ($i = 0; $i < 5 - $realExamPaper['recommendation_value']; $i++) {?>
					<span class="unselected-star"></span>
				<?php } ?>
			</div>
		</div>
		<div class="inline-block paper-item-opt">
			<?php if($realExamPaper['practise_times'] > 0) { ?>
			已做过<?php echo $realExamPaper['practise_times']; ?>次
			<?php } ?>
			<a class="btn blue-btn" href="<?php echo Yii::app()->createUrl("/realExamPaper/newPractise", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId, 'exam_paper_id' => $realExamPaper['id'], "return_url" => urlencode(Yii::app()->request->url)))?>">开始模考</a>
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