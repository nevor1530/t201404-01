<div class="do-paper">
	<div class="paper-left-column">
		<div id="clock" class="clock">00:00</div>
		<div class="btn red-btn">交卷</div>
		<div class="btn green-btn">下次再做</div>
	</div>
	<div class="paper-right-column">
		<div class="chapter-herder">专项训练：【<?php echo $examPointName; ?>】</div>
		<div class="content">
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
							<div style="float:left"><?php echo $answerOption['index'];?>. </div>
							<div> <?php echo $answerOption['description'];?></div>
						</div>
					<?php } ?>
				</div>
				<div class="answers">
					<?php foreach ($question['answerOptions'] as $answerOption) { ?>
						<?php if ($question['questionType'] == 0 || $question['questionType'] == 2) { ?>
							<label class="radio inline" style="margin-right: 10px;">
								<input type="radio" name="ChoiceQuestionForm[answer]" value="<?php echo $answerOption['index'];?>">
								<label><?php echo $answerOption['index']; ?></label>
							</label>
						<?php } else { ?>
							<label class="checkbox inline" style="margin-right: 10px;">
								<input type="checkbox" name="ChoiceQuestionForm[answer][]" value="<?php echo $answerOption['index'];?>">
								<label><?php echo $answerOption['index']; ?></label>
							</label>
						<?php } ?>	
					<?php } ?>
					<a class="favorite hover-origen" href="<?php echo Yii::app()->createUrl("/examPoint/ajaxAddQustionToFavorites", array("question_id"=>$question[id]));?>">收藏本题</a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
var s=0,m=0; 
function second(){  
	if(s>0 && (s%60)==0){m+=1;s=0;}  
	t = (s < 10 ? ("0" + s) : s);
	t = ":" + t;
	t = (m < 10 ? ("0" + m) : m) + t;
	document.getElementById("clock").innerHTML =t;  
	s+=1;  
}  
setInterval("second()",1000);
function pauseclock(){clearInterval(s);}  
function stopclock(){clearInterval(s);m=h=s=0;}  

$(function(){
	$(".favorite").on('click', function(e){
		e.preventDefault();
		$this = $(this);
		$.post($this.attr("href"), function(data){
			if (data.status === 0) {
				if (data.action == 'cancel') {$this.className = 'favorite hover-origen';}
				else if (data.action == 'add') {$this.className = 'favorite hover-origen favorite-chosen';}
			} else {
				alert(data.errMsg);
			}
		}, "json"); 	
	}); 
});

</script>  
