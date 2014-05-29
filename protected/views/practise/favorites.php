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
		function genExamPointHtml($examPoint) {
			$html = '<div class="level">';
			$html .= '	<div class="item">';
			$html .= '		<span class="title"><span class="bold">' . $examPoint['name'] . '</span><span class="font-size12">(共 ' . $examPoint['favorite_question_count'] . '道收藏)</span></span>';
			$html .= '		<button class="pull-right button">练习</button>';
			$html .= '		<a class="pull-right" href="#">查看题目</a>';
			$html .= '	</div>';
			
			$subExamPoints = $examPoint['sub_exam_points'];
			foreach ($subExamPoints as $subExamPoint) {
				$html .= '	<div class="sublevel">';
				$html .= genExamPointHtml($subExamPoint);
				$html .= '	</div>';
			}
			$html .= '</div>';
			return $html;
		}
		
		foreach ($examPoints as $examPoint) {
			echo genExamPointHtml($examPoint);
		}
		?>
	</div>
</div>

