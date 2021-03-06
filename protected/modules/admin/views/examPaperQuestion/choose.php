<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	$examPaperModel->name=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	'题目管理'=>array('/admin/examPaperQuestion/index', 'exam_paper_id'=>$examPaperModel->exam_paper_id),
	'挑选题目'
);

$this->menu=array(
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:<?php echo $hideAdvancedSearch ? 'none' : 'block'?>">
<?php $this->renderPartial('_search',array(
	'questionFilterForm' => $questionFilterForm,
	'questionTypes' => $questionTypes,
	'examPaperListData'=>$examPaperListData,
	'examPointListData' => $examPointListData,
)); ?>
</div><!-- search-form -->

<?php
$prev_material_id = null; 
foreach ($questionList as $question) { 
	if (isset($question['material_id']) && $question['material_id'] != null && $question['material_content'] != null) { 
		if ($prev_material_id != $question['material_id']) {
			$prev_material_id = $question['material_id'];
?>
<div style="margin-top:40px;">
	<div style="margin-bottom:5px;">
		<span style="font-size:20px;">材料预览：</span>
		<span class="pull-right">
			<?php 
			$htmlOptions = array('id'=>'question_'.$question['id']);
			$htmlOptions['class'] = 'js-exam-paper-question-check';
			$htmlOptions['data-material_id'] = $question['material_id'];
			isset($question['is_sequenced']) && $question['is_sequenced'] && $htmlOptions['disabled'] = true;
			echo CHtml::checkBox('', isset($question['is_chosen']) && $question['is_chosen'], $htmlOptions);?>
			<label class="label" for="<?php echo 'question_'.$question['id'];?>">加入</label>
		</span>
	</div>
	<div>
		<div style="border-top:dashed 1px #000;"><?php echo $question['material_content'] ?></div>
	</div>
</div>
<?php } ?>
<div style="margin-top:20px;margin-left:40px">
<?php } else { ?>
<div style="margin-top:40px;">
<?php } ?>

	<div style="margin-bottom:5px;">
		<a style="margin-right:20px"><?php echo '#' . $question['id'] ?></a>
		<?php if (!isset($question['material_id'])):?>
			<span class="pull-right">
				<?php 
				$htmlOptions = array('id'=>'question_'.$question['id']);
				$htmlOptions['class'] = 'js-exam-paper-question-check';
				$htmlOptions['data-question_id'] = $question['id'];
				isset($question['is_sequenced']) && $question['is_sequenced'] && $htmlOptions['disabled'] = true;
				echo CHtml::checkBox('', isset($question['is_chosen']) && $question['is_chosen'], $htmlOptions);?>
				<label class="label" for="<?php echo 'question_'.$question['id'];?>">加入</label>
			</span>
		<?php endif;?>
	</div>
	<div style="padding:0 0 10px 10px; border-top:dashed 1px #000;background-color:#EEEEEE;">
		<div class="row" style="padding-left:30px;padding-top:10px">
			<div style="width:40px;float:left">题干:  </div>
			<div><?php echo $question['content'];?></div>
		</div>
		
		<div class="row" style="padding-left:30px;padding-top:10px">
			<div style="padding-bottom:10px;">选项：  </div>
			<div>
				<?php foreach ($question['answerOptions'] as $answerOption) {?>
					<div style="float:left"><?php echo $answerOption['index'] . ". "?></div>
					<div><?php echo $answerOption['description'];?></div>
				<?php }?>
			</div>
		</div>
		
		<div class="row" style="padding-left:30px;padding-top:10px">
			正确答案：<?php echo $question['answer']; ?>
		</div>
		
		<?php if (isset($question['analysis'])) { ?> 
			<div class="row" style="padding-left:30px;padding-top:10px">
				<div style="width:40px;float:left">解析:</div>
				<div><?php echo $question['analysis'];?></div>
			</div>
		<?php } ?>
		
		<?php if (isset($question['questionExamPoints'])) { ?> 
		<div class="row" style="padding-left:30px;padding-top:10px">
			考点：
			<?php foreach ($question['questionExamPoints'] as $questionExamPoint) {?>
				<span style="padding:2px 30px;background-color:#BBBBBB;margin-right:20px"><?php echo $questionExamPoint; ?></span>
			<?php } ?>
		</div>
		<?php } ?>
		
	</div>
</div>
<?php } ?>

<style type="text/css">
ul.yiiPager {
	float:right;
}

ul.yiiPager li {
    font-size:15px;
}
</style>

<div id="pager" style="padding-top:30px;padding-bottom:40px;">    
<?php
$this->widget('CLinkPager',array(    
	'header'=>'',    
	'firstPageLabel' => '首页',    
	'lastPageLabel' => '末页',    
	'prevPageLabel' => '上一页',    
	'nextPageLabel' => '下一页',    
	'pages' => $pages,    
	'maxButtonCount'=>6   
));?>    
</div>

<?php 
$exam_paper_question_options = array(
	'exam_paper_id'=>$examPaperModel->primaryKey,
);
$exam_paper_question_options = CJavaScript::encode($exam_paper_question_options);
$exam_paper_question_url = Yii::app()->createUrl('/admin/examPaperQuestion/operate');

$exam_paper_question_script = <<< END
jQuery(".js-exam-paper-question-check").on("change", function(){
	var jQthis = jQuery(this);
	var params = $exam_paper_question_options;
	if (jQthis.data("question_id")){
		params['question_id'] = jQthis.data("question_id");
	} else {
		params['material_id'] = jQthis.data("material_id");
	}
	params['action'] = this.checked ? 'add' : 'remove';
	jQuery.ajax({
		url: "$exam_paper_question_url",
		data: params,
		dataType: 'json',
		type: 'POST',
		success: function(data){
			if (data.status === 0){
			} else {
				alert(data.errMsg);
				location.reload();
			}
		}
	});
});
END;
Yii::app()->clientScript->registerScript(__CLASS__.'#exam_paper_question', $exam_paper_question_script);?>

