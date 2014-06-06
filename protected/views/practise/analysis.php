<div class="do-paper">
	<div class="paper-left-column">
		<div class="btn green-btn"><?php echo $pageName; ?></div>
	</div>
	<div class="paper-right-column">
		<div class="chapter-herder"><?php echo $analysisName; ?></div>
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
					<a class="favorite hover-origen <?php echo ($question['is_favorite'] ? "favorite-chosen" : ""); ?>" href="<?php echo Yii::app()->createUrl("/practise/ajaxAddQustionToFavorites", array("question_id"=>$question['questionId']));?>">收藏本题</a>
				</div>
				<div class="analysis">
					<div class="analysis-item">
						<div class="analysis-item-title">考点</div>
						<div class="analysis-item-content">
							<?php if ($question['questionExamPoints']) {
								foreach ($question['questionExamPoints'] as $examPoint) {
									echo $examPoint . '&nbsp';
								}
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
	</div>
</div>