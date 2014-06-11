<div class="do-paper">
	<div class="paper-left-column">
		<div id="clock" class="clock"></div>
		<div class="btn red-btn">
			<a href="<?php echo Yii::app()->createUrl("/realExamPaper/completePractise", array("exam_bank_id"=>$this->examBankId,'subject_id'=>$this->curSubjectId, "exam_paper_instance_id" => $examPaperInstanceId, "return_url" => $returnUrl));?>">交卷</a>
		</div>
		<div class="btn green-btn">
			<a href="<?php echo ($returnUrl != null? urldecode($returnUrl) : Yii::app()->createUrl("/realExamPaper/list", array("exam_bank_id"=>$this->examBankId,'subject_id'=>$this->curSubjectId, 'is_recommendation' => true)));?>">下次再做</a>
		</div>
	</div>
	<div class="paper-right-column">
		<div class="chapter-herder"><?php echo $paperName?></div>
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
			<form id="answerForm" method="post" action="<?php echo Yii::app()->createUrl("/realExamPaper/ajaxSubmitAnswer");?>">
				<input type="hidden" name="answerForm[examPaperInstanceId]" value="<?php echo $examPaperInstanceId;?>">
				<input type="hidden" id="answerForm[questionId]" name="answerForm[questionId]">
				<input type="hidden" id="answerForm[answer]" name="answerForm[answer]">
				<input type="hidden" id="answerForm[remainTime]" name="answerForm[remainTime]">
			</form>
			
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
							<div class="option-item">
								<div style="float:left"><?php echo  chr($answerOption['index'] + 65);?>. </div>
								<div> <?php echo $answerOption['description'];?></div>
							</div>
						<?php } ?>
					</div>
					<div class="answers">
						<?php foreach ($question['answerOptions'] as $answerOption) { ?>
							<?php if ($question['questionType'] == 0 || $question['questionType'] == 2) { ?>
								<label class="radio inline" style="margin-right: 10px;">
									<input type="radio" <?php if ($answerOption['isSelected']) echo "checked=\"checked\""?> onclick="submitAnswer(<?php echo $question['questionId'];?>)" name="answer[<?php echo $question['questionId'];?>]" value="<?php echo $answerOption['index'];?>">
									<label><?php echo chr($answerOption['index'] + 65); ?></label>
								</label>
							<?php } else { ?>
								<label class="checkbox inline" style="margin-right: 10px;">
									<input type="checkbox" <?php if ($answerOption['isSelected']) echo "checked"?> onclick="submitAnswer(<?php echo $question['questionId'];?>)" name="answer[<?php echo $question['questionId'];?>]" value="<?php echo $answerOption['index'];?>">
									<label><?php echo chr($answerOption['index'] + 65); ?></label>
								</label>
							<?php } ?>	
						<?php } ?>
						</form>
						<a class="favorite hover-origen <?php echo ($question['is_favorite'] ? "favorite-chosen" : ""); ?>" href="<?php echo Yii::app()->createUrl("/realExamPaper/ajaxAddQustionToFavorites", array("question_id"=>$question['questionId']));?>">收藏本题</a>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
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

var m=<?php echo floor($remainTime/60);?>;
var s=<?php echo $remainTime%60;?>;
function second(){  
	if (s == 0 && m == 0) {
		window.location.href="<?php echo Yii::app()->createUrl("/realExamPaper/completePractise", array("exam_bank_id"=>$this->examBankId,'subject_id'=>$this->curSubjectId, "exam_paper_instance_id" => $examPaperInstanceId, "return_url" => $returnUrl));?>";
		return;	
	}
	
	if (s == 0 && m >= 1){
		m=m-1;
		s=59;
	} else {
		s=s-1;  
	}  
	
	t = (s < 10 ? ("0" + s) : s);
	t = ":" + t;
	t = (m < 10 ? ("0" + m) : m) + t;
	document.getElementById("clock").innerHTML =t;  
	
}  
second();
setInterval("second()",1000);
function pauseclock(){clearInterval(s);}  
function stopclock(){clearInterval(s);m=h=s=0;}  

function submitAnswer(questionId) {
	var radioName = 'answer[' + questionId + ']';
	var answer = $("input[name='" + radioName + "']:checked").val();
	
	var answer = [];
	$("input[name='" + radioName + "']:checked").each(function(){
    	answer.push($(this).val());
    });
	
	document.getElementById("answerForm[questionId]").value = questionId;
	document.getElementById("answerForm[answer]").value = answer;
	document.getElementById("answerForm[remainTime]").value = m*60+s;
	$("#answerForm").submit();
}

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
	
	$("#answerForm").submit(function() {
		var options = { 
			url: $(this).attr("action"),
			type: 'post',
			dataType: 'json',
			data: $("#answerForm").serialize(),
			success: function(data) {
				if (data.status === 1) {
					alert(data.errMsg);
				}
			}
		};
		$.ajax(options); 
		return false;
	});
});

gotoTop(window.screen.height);
</script>