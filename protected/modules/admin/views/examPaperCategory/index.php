<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷分类'=>array('/admin/category/index', 'subject_id'=>$subjectModel->subject_id),
	$categoryModel->name,
);

$this->menu=array(
	array('label'=>'填加试卷','url'=>array('create', 'category_id'=>$categoryModel->category_id)),
);
?>

<h3>类别<?php echo $categoryModel->name; ?>包含试卷</h3>

<?php 
Yii::app()->clientScript->registerCoreScript('jquery');

$moveScript = <<< END
function(){
	jQthis = jQuery(this);
	jQuery.post(jQthis.attr("href"), function(data){
		if (data.status === 0){
			location.reload();
		} else {
			alert(data.errMsg);
		}
	}, "json");
	return false;
}
END;

$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-paper-category-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableSorting'=>false,
	'columns'=>array(
		'examPaperName',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{up} {down} {delete}',
			'buttons'=>array(
				'up' => array(
					'label'=>'上移',
					'url'=>'Yii::app()->controller->createUrl("/admin/examPaperCategory/move",array("direction"=>"up","id"=>$data->primaryKey))',
					'icon'=>'arrow-up',
					'click'=>$moveScript,
				),
				'down' => array(
					'label'=>'下移',
					'url'=>'Yii::app()->controller->createUrl("/admin/examPaperCategory/move",array("direction"=>"down","id"=>$data->primaryKey))',
					'icon'=>'arrow-down',
					'click'=>$moveScript,
				),
			),
			'htmlOptions'=>array(
				'style'=>'width: 70px;',
			),
		),
	),
)); ?>
