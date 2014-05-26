<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

//$this->pageTitle=Yii::app()->name . ' - ' . $examBankName;
$this->breadcrumbs=array(
	'Login',
);
?>

<!-- 专项训练，真题模考，我的练习 单独的页面内容 -->
<ul class="subfunction-list">
	<li class="current"><a href="#">练习历史</a></li>
    <li><a href="#">我的错题</a></li>
    <li><a href="#">我的收藏</a></li>
</ul>

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
	function genExamPointHtml($examPoint) {
		$totalQuestionCount = $examPoint['question_count'];
		$finishedQuestionCount = $examPoint['finished_question_count'];
		$correctQuestionCount = $examPoint['correct_question_count'];
		$correctRate = $totalQuestionCount == 0 ? 0 : $correctQuestionCount / $totalQuestionCount;
		
		$html = '<div class="level">';
		$html .= '	<div class="item">';
		$html .= '		<div class="title name-column">' . $examPoint['name'] . '</div>';
		$html .= '		<button class="button button-column">练习</button>';
		$html .= '		<div class="rate-column">' . $correctRate . '</div>';
		$html .= '		<div class="done-questions-column">' . $finishedQuestionCount . '道</div>';
		$html .= '		<div class="process-column">';
		$html .= '			<div class="process-bar"><div class="rate-bar" style="width:' . $correctRate * 100 . '%"></div></div>';
		$html .= '		' . $finishedQuestionCount . '/' . $totalQuestionCount . '道';
		$html .= '		</div>';
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