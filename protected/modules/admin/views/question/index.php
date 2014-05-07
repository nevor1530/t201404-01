<?php
$this->breadcrumbs=array(
	'试题管理',
);

$this->menu=array(
	array('label'=>'添加选择题','url'=>array('createChoiceQuestion', 'subject_id'=>$subject_id)),
	array('label'=>'添加填空题','url'=>array('createBlankFillingQuestion', 'subject_id'=>$subject_id)),
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


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
	'examPaperListData'=>$examPaperListData,
)); ?>
</div><!-- search-form -->


