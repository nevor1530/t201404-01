<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理',
);

$this->menu=array(
	array('label'=>'添加选择题','url'=>array('createChoiceQuestion', 'subject_id'=>$subject_id)),
	array('label'=>'添加判断题','url'=>array('createTrueOrFalseQuestion', 'subject_id'=>$subject_id)),
	array('label'=>'添加材料题','url'=>array('createMaterialQuestion', 'subject_id'=>$subject_id)),
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
	if (isset($question['material_id']) && $question['material_id'] !== null && $question['material_content'] !== null) { 
		if ($prev_material_id != $question['material_id']) {
			$prev_material_id = $question['material_id'];
?>
<div style="margin-top:40px;">
	<div style="margin-bottom:5px;">
		<span style="font-size:20px;">材料预览：</span>
		<a class="pull-right" href="javascript:if(confirm('确认删除该题目吗？'))location='<?php echo Yii::app()->createUrl("/admin/question/deleteQuestion", array("subject_id"=> $subject_id,"material_id"=>$question['material_id']));?>'">删除</a>
		<span style="margin-left:5px;margin-right:5px" class="pull-right">|</span>
		<a class="pull-right" href="<?php echo Yii::app()->createUrl("/admin/question/updateMaterial", array("subject_id"=> $subject_id, "material_id"=>$question['material_id'], "return_url" => urlencode(Yii::app()->request->url)))?>" style="margin-right:5px">编辑材料</a>
		<span style="margin-left:5px;margin-right:5px" class="pull-right">|</span>
		<a class="pull-right" href="<?php echo Yii::app()->createUrl("/admin/question/viewMaterialQuestion", array("subject_id"=> $subject_id, "material_id"=>$question['material_id']))?>" style="margin-right:5px">添加题目</a>
	</div>
	<div>
		<div style="border-top:dashed 1px #000;"><?php echo $question[material_content] ?></div>
	</div>
</div>
<?php } ?>
<div style="margin-top:20px;margin-left:40px">
<?php } else { ?>
<div style="margin-top:40px;">
<?php } ?>

	<div style="margin-bottom:5px;">
		<a style="margin-right:20px"><?php echo '#' . $question['id'] ?></a>
		<a class="pull-right" href="javascript:if(confirm('确认删除该题目吗？'))location='<?php echo Yii::app()->createUrl("/admin/question/deleteQuestion", array("subject_id"=> $subject_id,"question_id"=>$question['id']));?>'">删除</a>
		<span style="margin-left:5px;margin-right:5px" class="pull-right">|</span>
		<a class="pull-right" href="<?php echo Yii::app()->createUrl("/admin/question/updateQuestion", array("subject_id"=> $subject_id,"question_id"=>$question['id'], "material_id" => 0, "return_url" => urlencode(Yii::app()->request->url)));?>" style="margin-right:5px">编辑题目</a>
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
		
		<?php if (isset($question['analysis']) && $question['analysis'] != null) { ?> 
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

