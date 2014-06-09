<div class="do-paper">
	<div class="paper-left-column">
		<div class="btn red-btn">查看报告</div>
		<div class="btn green-btn">查看解析</div>
	</div>
	<div class="paper-right-column paper-report">
		<div class="chapter-herder"><?php echo $examPaperName; ?></div>
		<div class="practice-time">练习时间：<?php echo $practiseStartTime; ?></div>
		<div class="content">
			<div class="chapter">
				<div class="inline-block half text-center">
					<div class="message">本次练习共<?php echo $totalQuestionCount; ?>道题，你答对了</div>
					<div class="status"><span class="number"><?php echo $correctQuestionCount; ?></span>道</div>
				</div>
				<div class="inline-block half text-center">
					<div class="message">答题所花时间</div>
					<div class="status"><span class="number"><?php echo $practiseElapsedTime; ?></span>分钟</div>
				</div>
			</div>
			<!-- 答题卡 -->
			<div class="chapter">
				<div class="answer-card-title">答题卡</div>
				<p></p>
				<?php 
				$index = 1;
				foreach ($questionBlocks as $questionBlock) {
					$questionBlockName = $questionBlock['name'];
					echo "<div><strong>$questionBlockName</strong></div>";
					$questions = $questionBlock['questions'];
					foreach ($questions as $question) {
						if ($question['my_answer'] == null) {
							echo "<button class=\"b-btn b-btn-mini btn-question\" type=\"button\">$index</button>";
						} else if ($question['is_correct']) {
							echo "<button class=\"b-btn b-btn-mini btn-question b-btn-success\" type=\"button\">$index</button>";
						} else {
							echo "<button class=\"b-btn b-btn-mini btn-question b-btn-danger\" type=\"button\">$index</button>";
						}
						$index++;
					}
					echo "<p></p>";
				} ?>
			</div>
			<!-- 本次考试情况 -->
			<div class="chapter">
				<div class="font-size16 bold">本次考试情况</div>
			</div>
			<!-- 分享 -->
			<div class="chapter"></div>
		</div>
	</div>
</div>