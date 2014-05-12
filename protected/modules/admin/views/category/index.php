<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷分类'
);

$this->menu=array(
	array('label'=>'创建分类','url'=>array('create', 'subject_id'=>$subjectModel->subject_id)),
);
?>

<h2><?php echo $subjectModel->name;?> 试卷分类</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'category-model-grid',
	'dataProvider'=>$model->search(),
	'type'=>array('hover', 'striped'),
	'columns'=>array(
		'name',
		array(
			'name'=>'paperCount',
			'htmlOptions'=>array(
					'style'=>'width: 70px;',
			),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'操作',
			'template'=>'{add_paper} {update} {delete}',
			'buttons'=>array(
				'add_paper' => array(
					'label'=>'查看试卷',
					'url'=>'Yii::app()->controller->createUrl("/admin/examPaperCategory/index",array("category_id"=>$data->primaryKey))',
					'icon'=>'eye-open',
				),
			),
			'htmlOptions'=>array(
					'style'=>'width: 70px;',
			),
		),
	),
)); ?>
