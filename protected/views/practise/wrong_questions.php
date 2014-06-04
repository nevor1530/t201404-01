<ul class="subfunction-list">
	<li><a href="<?php echo Yii::app()->createUrl("/practise/history", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">练习历史</a></li>
    <li class="current">我的错题</li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/favorites", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的收藏</a></li>
</ul>

<div class="content">
	<div class="bold">共<span style="color: #00a0e9"><?php echo $totalWrongQuestionCount; ?></span>道错题
    <div class="exam-point-tree">
    	<?php 
		function genExamPointHtml($examBankId, $subjectId, $examPoint) {
			$wrongQuestionCount = $examPoint['wrong_question_count'];
			if ($wrongQuestionCount > 0) {
				$newPractiseUrl = Yii::app()->createUrl("/practise/newWrongQuestionPractise", array(
					"exam_bank_id"=>$examBankId, 
					"subject_id"=>$subjectId, 
					"exam_point_id"=>$examPoint['id'],
					'return_url'=>urlencode(Yii::app()->request->url),
				));
				
				$viewWrongQuestionsUrl = Yii::app()->createUrl("/practise/viewWrongQuestionAnalysis", array(
					"exam_bank_id"=>$examBankId, 
					"subject_id"=>$subjectId, 
					"exam_point_id"=>$examPoint['id']
				));
			}
		
			$html = '<div class="level">';
			$html .= '	<div class="item">';
			$html .= '		<span class="title"><span class="bold">' . $examPoint['name'] . '</span><span class="font-size12">(共 ' . $wrongQuestionCount . '道错题)</span></span>';
			$html .= '		<a class="pull-right button" ' . ($wrongQuestionCount > 0 ? 'href="'.$newPractiseUrl.'"' : '') . '>练习</a>';
			$html .= '		<a class="pull-right" ' . ($wrongQuestionCount > 0 ? 'href="'.$viewWrongQuestionsUrl.'"' : '') . '>查看题目</a>';
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
