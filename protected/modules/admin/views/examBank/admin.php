<?php
$this->breadcrumbs=array(
	'题库管理'
);

$this->menu=array(
	array('label'=>'创建题库','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('exam-bank-model-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>题库管理</h1>

<?php 
$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-bank-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>array('hover', 'striped'),
	'columns'=>array(
		'exam_bank_id',
		'name',
		'price',
		array(  
            'type'=>'raw',
            'value'=> 'CHtml::image(Yii::app()->baseUrl ."/" . Constants::$EXAM_BANK_ICON_DIR_PATH . $data->icon, "", array("width"=>"60px" ,"height"=>"60px"))',
            'header'=>'题库图标',  
		),
		  
		array(
			'class'=>'LinksColumn',
			'name'=>'subjects',
			'urlExpression'=>'Yii::app()->createUrl("/admin/subject/view", array("id"=>$data->primaryKey))',
			'linkHtmlOptions'=>array('class'=>'links_column_item'),
			'header'=>'课程',
		),
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=>'操作',
			'template'=>'{update} {add_subject} {delete}',
			'buttons'=>array(
				'add_subject' => array(
					'label'=>'增加课程',
					'url'=>'Yii::app()->controller->createUrl("/admin/subject/create",array("exam_bank_id"=>$data->primaryKey))',
					'icon'=>'plus',
				),
			),
		),
	)
));
?>
