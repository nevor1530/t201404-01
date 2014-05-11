<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'真题推荐'
);

$this->menu=array(
	array('label'=>'增加试卷','url'=>array('create', 'subject_id'=>$subjectModel->subject_id)),
);
?>

<h2>真题推荐</h2>

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
	'id'=>'paper-recommendation-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>array('hover', 'striped'),
	'columns'=>array(
		'examPaperName',
		array(
			'name'=>'difficuty',
			'htmlOptions'=>array('style'=>'width:50px;'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'操作',
			'template'=>'{up} {down} {update} {delete}',
			'buttons'=>array(
				'up' => array(
					'label'=>'上移',
					'url'=>'Yii::app()->controller->createUrl("/admin/paperRecommendation/move",array("direction"=>"up","id"=>$data->primaryKey, "subject_id"=>$data->subject_id))',
					'icon'=>'arrow-up',
					'click'=>$moveScript,
				),
				'down' => array(
					'label'=>'下移',
					'url'=>'Yii::app()->controller->createUrl("/admin/paperRecommendation/move",array("direction"=>"down","id"=>$data->primaryKey, "subject_id"=>$data->subject_id))',
					'icon'=>'arrow-down',
					'click'=>$moveScript,
				),
			),
			'htmlOptions'=>array(
				'style'=>'width: 70px;',
			),
		),
	),
)); 

?>
