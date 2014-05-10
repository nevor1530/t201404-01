<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理',
);

$this->menu=array(
	array('label'=>'添加选择题','url'=>array('createChoiceQuestion', 'subject_id'=>$subject_id)),
	array('label'=>'添加判断题','url'=>array('createTrueOrFalseQuestion', 'subject_id'=>$subject_id)),
	array('label'=>'添加题目材料','url'=>array('createMaterialQuestion', 'subject_id'=>$subject_id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$questionModel,
	'examPaperListData'=>$examPaperListData,
)); ?>
</div><!-- search-form -->

<?php foreach ($questionList as $question) { ?>
	<div style="margin-top:40px; padding-top:10px; border-top:dashed 1px #000;background-color:#EEEEEE;">
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


