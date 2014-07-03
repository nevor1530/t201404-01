<div class="do-paper">
	<div class="paper-left-column">
		<div id="clock" class="clock"></div>
		<div class="btn red-btn">
			<a class="submit-paper" href="<?php echo Yii::app()->createUrl("/examPoint/completePractise", array("exam_bank_id"=>$this->examBankId,'subject_id'=>$this->curSubjectId, "exam_paper_instance_id" => $examPaperInstanceId, "return_url" => $returnUrl));?>">交卷</a>
		</div>
		<div class="btn green-btn">
			<a href="<?php echo ($returnUrl != null? urldecode($returnUrl) : Yii::app()->createUrl("/examPoint/index", array("exam_bank_id"=>$this->examBankId,'subject_id'=>$this->curSubjectId)));?>">下次再做</a>
		</div>
	</div>
	<div class="paper-right-column">
		<div class="chapter-herder"><?php echo $practiseName; ?></div>
		<div class="content">
			<form id="answerForm" method="post" action="<?php echo Yii::app()->createUrl("/examPoint/ajaxSubmitAnswer");?>">
				<input type="hidden" name="answerForm[examPaperInstanceId]" value="<?php echo $examPaperInstanceId;?>">
				<input type="hidden" id="answerForm[questionInstanceId]" name="answerForm[questionInstanceId]">
				<input type="hidden" id="answerForm[questionId]" name="answerForm[questionId]">
				<input type="hidden" id="answerForm[answer]" name="answerForm[answer]">
				<input type="hidden" id="answerForm[time]" name="answerForm[time]">
			</form>
			
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
								<input type="radio" <?php if ($answerOption['isSelected']) echo "checked=\"checked\""?> onclick="submitAnswer(<?php echo $question['questionId'];?>,<?php  echo $question['questionInstanceId'];?>)" name="answer[<?php echo $question['questionInstanceId'];?>]" value="<?php echo $answerOption['index'];?>">
								<label><?php echo chr($answerOption['index'] + 65); ?></label>
							</label>
						<?php } else { ?>
							<label class="checkbox inline" style="margin-right: 10px;">
								<input type="checkbox" <?php if ($answerOption['isSelected']) echo "checked"?> onclick="submitAnswer(<?php echo $question['questionId'];?>,<?php  echo $question['questionInstanceId'];?>)" name="answer[<?php echo $question['questionInstanceId'];?>]" value="<?php echo $answerOption['index'];?>">
								<label><?php echo chr($answerOption['index'] + 65); ?></label>
							</label>
						<?php } ?>	
					<?php } ?>
					</form>
					<a class="favorite hover-origen <?php echo ($question['is_favorite'] ? "favorite-chosen" : ""); ?>" href="<?php echo Yii::app()->createUrl("/examPoint/ajaxAddQustionToFavorites", array("question_id"=>$question['questionId']));?>">收藏本题</a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
var m=<?php echo floor($elapsedTime/60);?>;
var s=<?php echo $elapsedTime%60;?>;
function second(){  
	if(s>0 && (s%60)==0){m+=1;s=0;}  
	t = (s < 10 ? ("0" + s) : s);
	t = ":" + t;
	t = (m < 10 ? ("0" + m) : m) + t;
	document.getElementById("clock").innerHTML =t;  
	s+=1;  
}  
second();
setInterval("second()",1000);
function pauseclock(){clearInterval(s);}  
function stopclock(){clearInterval(s);m=h=s=0;}  

function submitAnswer(questionId, questionInstanceId) {
	var radioName = 'answer[' + questionInstanceId + ']';
	var answer = $("input[name='" + radioName + "']:checked").val();
	
	var answer = [];
	$("input[name='" + radioName + "']:checked").each(function(){
    	answer.push($(this).val());
    });
	
	document.getElementById("answerForm[questionInstanceId]").value = questionInstanceId;
	document.getElementById("answerForm[questionId]").value = questionId;
	document.getElementById("answerForm[answer]").value = answer;
	document.getElementById("answerForm[time]").value = m*60+s;
	$("#answerForm").submit();
}

var unansweredQuestionsCount = <?php echo isset($unansweredQuestionsCount)? $unansweredQuestionsCount:0;?>;
$(function(){
	$(".submit-paper").on('click', function(e) {
		e.preventDefault();
		$this = $(this);
		var href = $this.attr("href");
		var content = '确定要交卷吗？';
		if (unansweredQuestionsCount > 0) {
			content = '还剩' + unansweredQuestionsCount + '道题未答完，' + content;
		}
		var options = {
 			title: '<?php echo $practiseName; ?>',
 			content: content,
 			confirmBtnLabel: '确定',
 			cancelBtnLabel: '取消',
 			confirmCallback: function(){
 				location.href=href;
 			}
 		};
 		jQuery.mydialog.show(options);
	});
	
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
				} else {
					unansweredQuestionsCount--;
				}
			}
		};
		$.ajax(options); 
		return false;
	});
});

gotoTop(window.screen.height);
$(".paper-left-column").scrollFix(0);
</script>  
