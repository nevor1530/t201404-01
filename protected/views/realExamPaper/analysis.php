<div class="do-paper">
	<div class="paper-left-column">
		<div class="btn green-btn"><?php echo $pageName; ?></div>
	</div>
	<div class="paper-right-column">
		<div class="chapter-herder"><?php echo $analysisName; ?></div>
		<ul class="question-block">
			<?php foreach ($questionBlocks as $questionBlock) {?>
			<li id="blockTitle<?php echo $questionBlock['id']; ?>">
				<a href="javascript:void(0);" onclick="displayQuestionBlock(<?php echo $questionBlock['id'];?>)"><?php echo $questionBlock['name'];?></a>
			</li>|
			<?php } ?>
		</ul>
		
		<?php foreach ($questionBlocks as $questionBlock) {?>
		<p id="blockDescription<?php echo $questionBlock['id']; ?>" style="padding: 0 13px;display:none"><?php echo $questionBlock['description'];?></p>
		<?php } ?>
		
		<div class="content">
			<?php 
			foreach ($questionBlocks as $questionBlock) {
				$questions = $questionBlock['questions'];
			?>
			<div id="block<?php echo $questionBlock['id'];?>" style="display:none">
				<?php 
				$prev_material_id = null; 
				foreach ($questions as $question) { 
					if (isset($question['material_id']) && $question['material_id'] !== null && $question['material_content'] !== null) { 
						if ($prev_material_id != $question['material_id']) {
							$prev_material_id = $question['material_id'];
				
				?>
				
				<div class="question">
					<div class="material">
						<div class="material-mark">材料题</div>
						<div class="material-content">
							<?php echo  $question['material_content'] ?>
						</div>
					</div>
					<div style="text-align: right">
						<a class="material-popup hover-origen">↑ 弹出材料</a>
					</div>
				</div>
				<?php }} ?>
				
				<div class="question">
					<div class="title">
						<div style="width:40px;float:left">题干: </div>
						<div><?php echo $question['content'] ;?></div>
					</div>
					<div class="options">
						<?php foreach ($question['answerOptions'] as $answerOption) { ?>
							<div class="option-item" style="<?php echo $answerOption['isSelected'] ? "color:#00b7ee" : "";?>">
								<div style="float:left"><?php echo  chr($answerOption['index'] + 65);?>. </div>
								<div> <?php echo $answerOption['description'];?></div>
							</div>
						<?php } ?>
					</div>
					
					<div class="answers">
						<?php 
						$correctAnswer = explode('|', $question['correct_answer']);
						for ($index = 0; $index < count($correctAnswer); $index++) {
							$correctAnswer[$index] = chr($correctAnswer[$index] + 65);
						}
						$correctAnswer = implode(',', $correctAnswer);
						?>
						<span>本题正确答案是：<?php echo $correctAnswer; ?></span>
						<?php if ($question['my_answer'] != null) { 
							$myanswer = explode('|', $question['my_answer']);
							for ($index = 0; $index < count($myanswer); $index++) {
								$myanswer[$index] = chr($myanswer[$index] + 65);
							}
							$myanswer = implode(',', $myanswer);
						?>
						<span style="margin-left:10px;">你的答案是：<?php echo $myanswer; ?></span>
						<?php } else { ?>
						<span style="margin-left:10px;">你没有回答这道题 </span>
						<?php } ?>
						<span style="margin-left:10px;">
							<?php if ($question['my_answer'] != null && $question['is_correct']) { echo '回答正确'; } ?>
							<?php if ($question['my_answer'] != null && !$question['is_correct']) { echo '回答错误'; } ?>
						</span>
						<a class="favorite hover-origen <?php echo ($question['is_favorite'] ? "favorite-chosen" : ""); ?>" href="<?php echo Yii::app()->createUrl("/realExamPaper/ajaxAddQustionToFavorites", array("question_id"=>$question['questionId']));?>">收藏本题</a>
					</div>
					
					<div class="analysis">
						<div class="analysis-item">
							<div class="analysis-item-title">考点</div>
							<div class="analysis-item-content">
								<?php 
								if (isset($question['questionExamPoints']) && $question['questionExamPoints'] != null) {
									foreach ($question['questionExamPoints'] as $examPoint) {
										echo $examPoint . '&nbsp';
									}
								} else { 
									echo '暂无考点';
								} ?>
							</div>
						</div>
						<div class="analysis-item">
							<div class="analysis-item-title">解析</div>
							<div class="analysis-item-content"><?php echo $question['analysis'] != null ? $question['analysis'] : '暂无解析'; ?></div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$(".favorite").on('click', function(e){
		e.preventDefault();
		$this = $(this);
		$.post($this.attr("href"), function(data){
			if (data.status === 0) {
				if (data.action == 'cancel') {
					$this.removeClass('favorite-chosen');
				} else if (data.action == 'add') {
					$this.addClass('favorite-chosen');
				}
			} else {
				alert(data.errMsg);
			}
		}, "json"); 	
	});
});

var totalBlockNum = <?php echo count($questionBlocks);?>;
var curDisplayQuestionBlockId = -1;

function displayQuestionBlock(questionBlockId) {
	if (curDisplayQuestionBlockId == questionBlockId) {
		return;
	}
	
	hideQuestionBlock(curDisplayQuestionBlockId);
	$("#blockTitle" + questionBlockId).addClass('current');
	$("#blockDescription" + questionBlockId).show();
	$("#block" + questionBlockId).show();
	curDisplayQuestionBlockId = questionBlockId;
}

function hideQuestionBlock(questionBlockId) {
	$("#blockTitle" + questionBlockId).removeClass('current');
	$("#blockDescription" + questionBlockId).hide();
	$("#block" + questionBlockId).hide();	
}

<?php if (count($questionBlocks) > 0 ) { ?>
displayQuestionBlock(<?php echo $questionBlocks[0]['id'];?>);	
<?php } ?>

</script>