<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	$examPaperModel->name=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	'模块管理',
);

$this->menu=array(
	array('label'=>'创建模块','url'=>array('create', 'exam_paper_id'=>$examPaperModel->primaryKey)),
);
?>

<h2>试卷 <?php echo $examPaperModel->name; ?> 模块管理</h2>

<?php 
Yii::app()->clientScript->registerCoreScript('jquery');

$moveScript = <<< 'END'
function(){
	$this = $(this);
	$.post($this.attr("href"), function(data){
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
	'id'=>'question-block-model-grid',
	'dataProvider'=>$model->search(),
	'enableSorting'=>false,
	'columns'=>array(
		'name',
		'time_length',
		'question_number',
		'score',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'操作',
			'template'=>'{up} {down} {update} {delete}',
			'buttons'=>array(
				'up' => array(
					'label'=>'上移',
					'url'=>'Yii::app()->controller->createUrl("/admin/questionBlock/move",array("direction"=>"up","id"=>$data->primaryKey, "exam_paper_id"=>$_GET["exam_paper_id"]))',
					'icon'=>'arrow-up',
					'click'=>$moveScript,
				),
				'down' => array(
					'label'=>'下移',
					'url'=>'Yii::app()->controller->createUrl("/admin/questionBlock/move",array("direction"=>"down","id"=>$data->primaryKey, "exam_paper_id"=>$_GET["exam_paper_id"]))',
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
