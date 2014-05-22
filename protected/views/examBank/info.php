<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - ' . $exam_bank_name;
$this->breadcrumbs=array(
	'Login',
);
?>

<style type="text/css">
.main {
	padding-top: 20px;
	margin: 0 auto;
}

.btn[disabled] {
	background-color: #006DCC;
	opacity: 1;
	min-width: 150px;
}

.subjects {
	margin-top: 15px;
	font-size: 0;
}

.subject {
	border-style: solid; 
	border-color: #a6cff8;
	border-width: 1px;
	padding: 10px 8px;
	font-size: 15px;
}

.active {
	background-color: #a6cff8;
	color: white;
}

.deactive {
	background-color: #FFFFFF;
}

.tabs {
	margin-top: 3px;
	font-size: 0;
}

.tab {
	border-style: solid; 
	border-color: #a6cff8;
	border-width: 1px;
	padding: 10px 5px;
	font-size: 14px;
	background-color: #d8e8fa;
	padding: 6px 30px;
}

.exam_point_tree {
	padding: 10px 20px 40px 20px;
	border-color: #cbe3ff;
	border-style: solid; 
	border-width: 1px;
}

.exam_point_tree .header {
	font-size: 15px;
	font-weight: 700;
	margin-bottom: 5px;
}

.exam_point_tree .divider {
	height:2px;
	border-top-color:#96c7ff; 
	border-top-style:solid; 
	border-top-width:2px;
	margin-left: -5px;
	margin-right: -5px;
}

.exam_point_item {
	margin-top: 8px;
	color: #003f82;
	font-size: 14px;
}

.exam_point_item .progress {
	margin-bottom: 0px;
	height: 15px;
}

.col-centered {
	float: none;
	margin: 0 auto;
}
</style>

<div class="main">
	<div class="row">
		<div class="offset2">
			<button class="btn btn-primary btn-large disabled" disabled="disabled"><?php echo $examBankName; ?></button>
		</div>
		<div class="offset2 subjects">
			<?php for ($i = 0; $i < count($subjects); $i++) { 
				$subject = $subjects[$i];
				$style = ($i == 0) ? "active" : "deactive";
			?>
			<button class="subject <?php echo $style; ?>"><?php echo $subject['name'];?></button>
			<?php } ?>
		</div>
		<div class="offset2 tabs">
			<button class="tab">专项训练</button>
			<button class="tab">真题模考</button>
			<button class="tab">我的练习</button>
		</div>
		<div class="offset2 exam_point_tree">
			<div class="row header">
				<div class="span3">专项名称</div>
				<div class="span3">答题进度</div>
				<div class="span1">答题量</div>
				<div class="span2">答题正确率</div>
			</div>	
			<div class="divider"></div>
			<?php 
				function genExamPointHtml($examPoint, $level) {
					$prefix = '';
					for ($i = 0; $i < $level * 7; $i++) {
						$prefix .= '&nbsp;';
					}
		
					if (count($examPoint['sub_exam_points']) > 0) {
						$downImg = Yii::app()->request->baseUrl . "/images/down-arrow.png";
						$arrowHtml = '<img src="' . $downImg . '" style="max-width:10px"/>&nbsp&nbsp';	
					} else {
						$leftImg = Yii::app()->request->baseUrl . "/images/left-arrow.png";
						$arrowHtml = '<img src="' . $leftImg . '" style="max-width:12px"/>&nbsp&nbsp';	
					}
    
					$html = '<div class="row exam_point_item">';
					$html .= '<div class="span3">' . $prefix . $arrowHtml . $examPoint['name'] . '</div>';
					$html .= '<div class="span3"><div class="pull-right" style="margin-left:15px;margin-bottom:2px;">10/20道</div><div class="progress"><div class="bar" style="width: 60%;"></div></div></div>';
					$html .= '<div class="span3">' . $examPoint['question_count'] . '道</div>';
					$html .= '<div class="span3"></div>';
					$html .= '</div>';
					
					foreach ($examPoint['sub_exam_points'] as $subExamPoint) {
						$html .= genExamPointHtml($subExamPoint, $level+1);
					}
					
					return $html;
				}
				
				
				for ($i = 0; $i < count($examPoints); $i++) {
					if ($i != 0) {
						echo '<div style="height:2px;margin-top:5px;border-top-color:#96c7ff;border-top-style:dashed;border-top-width:1px;"></div>';
					}

					$examPoint = $examPoints[$i];
					echo genExamPointHtml($examPoint, 0);
				}
			?>
		</div>
	</div>
</div>