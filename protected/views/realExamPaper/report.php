<div class="do-paper">
	<div class="paper-left-column">
		<div class="btn red-btn">查看报告</div>
		<div class="btn green-btn">
			<a href="<?php echo Yii::app()->createUrl("/realExamPaper/viewAnalysis", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId, 'exam_paper_instance_id' => $exam_paper_instance_id))?>">查看解析</a>
		</div>
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
				<div class="point-practice-table-header report-table-header">
					<div class="name-column">专项名称</div>
				    <div class="rate-column">答题正确率</div>
				    <div class="done-questions-column">作答数量</div>
				    <div class="done-questions-column">涉及题目</div>
				</div>
				<div class="exam-point-tree point-practice report-exam-point-tree">
				<?php 
				function genExamPointHtml($examBankId, $subjectId, $examPoint) {
					$totalQuestionCount = $examPoint['question_count'];
					$finishedQuestionCount = $examPoint['finished_question_count'];
					$correctQuestionCount = $examPoint['correct_question_count'];
					$correctQuestionRate = round($finishedQuestionCount == 0 ? 0 : $correctQuestionCount / $finishedQuestionCount, 3);
			
					$html = '<div class="level">';
					$html .= '	<div class="item">';
					$html .= '		<div class="title name-column">' . $examPoint['name'] . '</div>';
					$html .= '		<div class="rate-column">' . $correctQuestionRate * 100 . '%</div>';
					$html .= '		<div class="done-questions-column">' . $finishedQuestionCount . '道</div>';
					$html .= '		<div class="done-questions-column">' . $totalQuestionCount . '道</div>';
					$html .= '	</div>';
					
					$subExamPoints = $examPoint['sub_exam_points'];
					foreach ($subExamPoints as $subExamPoint) {
						$html .= '	<div class="sublevel">';
						$html .= genExamPointHtml($examBankId, $subjectId, $subExamPoint);
						$html .= '	</div>';
					}
					$html .= '</div>';
					return $html;
				}
				
				foreach ($examPoints as $examPoint) {
					echo genExamPointHtml($this->examBankId, $this->curSubjectId, $examPoint);
				}
				?>
				</div>
			</div>
			<!-- 分享 -->
			<div class="chapter">
				<span>分享报告：</span>
				<div class="sina-weibo"></div>
			</div>
		</div>
	</div>
</div>