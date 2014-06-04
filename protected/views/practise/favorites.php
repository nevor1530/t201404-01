<!-- 专项训练，真题模考，我的练习 单独的页面内容 -->
<ul class="subfunction-list">
	<li><a href="<?php echo Yii::app()->createUrl("/practise/history", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">练习历史</a></li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/wrongQuestions", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的错题</a></li>
    <li class="current">我的收藏</li>
</ul>

<div class="content">
	<div class="bold">共<span style="color: #00a0e9"><?php echo $totalFavoriteQuestionCount; ?></span>道收藏
    <div class="exam-point-tree">
    	<?php 
		function genExamPointHtml($examBankId, $subjectId, $examPoint) {
			$favoriteQuestionCount = $examPoint['favorite_question_count'];
			if ($favoriteQuestionCount > 0) {
				$newPractiseUrl = Yii::app()->createUrl("/practise/newFavoriteQuestionPractise", array(
					"exam_bank_id"=>$examBankId, 
					"subject_id"=>$subjectId, 
					"exam_point_id"=>$examPoint['id'],
					'return_url'=>urlencode(Yii::app()->request->url),
				));
				
				$viewFavoriteQuestionsUrl = Yii::app()->createUrl("/practise/viewFavoriteQuestionAnalysis", array(
					"exam_bank_id"=>$examBankId, 
					"subject_id"=>$subjectId, 
					"exam_point_id"=>$examPoint['id']
				));
			}
			
			$html = '<div class="level">';
			$html .= '	<div class="item">';
			$html .= '		<span class="title"><span class="bold">' . $examPoint['name'] . '</span><span class="font-size12">(共 ' . $examPoint['favorite_question_count'] . '道收藏)</span></span>';
			$html .= '		<a class="pull-right button" ' . ($favoriteQuestionCount > 0 ? ' href="'.$newPractiseUrl.'"' : '') . '>练习</a>';
			$html .= '		<a class="pull-right"' . ($favoriteQuestionCount > 0 ? 'href="'.$viewFavoriteQuestionsUrl.'"' : '') . '>查看题目</a>';
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

