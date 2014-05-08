<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷管理'
);

$this->menu=array(
	array('label'=>'创建试卷','url'=>array('create', 'subject_id'=>$subjectModel->primaryKey)),
);
?>

<h1>试卷管理</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-paper-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'NDataLinkColumn',
			'name'=>'name',
			'urlExpression'=>'Yii::app()->createUrl("/admin/examPaper/view", array("id"=>$data->primaryKey))',
		),
		'name',
		array(
			'class'=>'CDataColumn',
			'name'=>'publish_time',
			'type'=>'date',
		),
		array(
			'class'=>'ExamPaperStatusColumn',
			'name'=>'status',
			'mapData'=>array(0=>'未录完', 1=>'未发布', 2=>'已发布')
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'操作',
			'template'=>'{question} {block} {update} {delete}',
			'buttons'=>array(
				'question' => array(
					'label'=>'题目管理',
					'url'=>'Yii::app()->controller->createUrl("/admin/subject/create",array("exam_bank_id"=>$data->primaryKey))',
					'icon'=>'th',
				),
				'block' => array(
					'label'=>'模块管理',
					'url'=>'Yii::app()->controller->createUrl("/admin/subject/create",array("exam_bank_id"=>$data->primaryKey))',
					'icon'=>'th-large',
				),
			),
			'htmlOptions'=>array(
				'style'=>'width: 70px',
			),
		),
	),
)); ?>
