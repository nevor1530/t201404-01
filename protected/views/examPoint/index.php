<div class="content">
	<div class="point-practice-table-header">
		<div class="name-column">专项训练</div>
	    <div class="button-column">随机练习</div>
	    <div class="rate-column">答题正确率</div>
	    <div class="done-questions-column">答题量</div>
	    <div class="process-column">答题进度</div>
	</div>
	
	<div class="exam-point-tree point-practice">
	<?php 
	function genExamPointHtml($examBankId, $subjectId, $examPoint) {
		$totalQuestionCount = $examPoint['question_count'];
		$finishedQuestionCount = $examPoint['finished_question_count'];
		$correctQuestionCount = $examPoint['correct_question_count'];
		$finishedQuestionRate = $totalQuestionCount == 0 ? 0 : $finishedQuestionCount / $totalQuestionCount;
		$correctQuestionRate = round($totalQuestionCount == 0 ? 0 : $correctQuestionCount / $totalQuestionCount, 3);

		if ($totalQuestionCount > 0) {
			$newPractiseUrl = Yii::app()->createUrl("/examPoint/newPractise", array(
				"exam_bank_id" => $examBankId, 
				"subject_id" => $subjectId, 
				"exam_point_id" => $examPoint['id'],
				"return_url" => urlencode(Yii::app()->request->url),
			));
		}		
		
		$html = '<div class="level">';
		$html .= '	<div class="item">';
		$html .= '		<div class="title name-column">' . $examPoint['name'] . '</div>';
		$html .= '		<a class="button button-column" ' . ($totalQuestionCount > 0 ? 'href="'.$newPractiseUrl.'"' : ''). '>练习</a>';
		$html .= '		<div class="rate-column">' . $correctQuestionRate . '</div>';
		$html .= '		<div class="done-questions-column">' . $finishedQuestionCount . '道</div>';
		$html .= '		<div class="process-column">';
		$html .= '			<div class="process-bar"><div class="rate-bar" style="width:' . $finishedQuestionRate * 100 . '%"></div></div>';
		$html .= '		' . $finishedQuestionCount . '/' . $totalQuestionCount . '道';
		$html .= '		</div>';
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
		echo genExamPointHtml($examBankId, $subjectId, $examPoint);
	}
	?>
	</div>
</div>