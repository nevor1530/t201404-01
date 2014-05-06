<?php
$this->menu=array(
	array('label'=>'创建题库','url'=>array('/admin/examBank/create')),
);

?>

<h1>题库管理</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'exam-bank-model-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'exam_bank_id',
		'name',
		'price',
		
		array(  
            'type'=>'raw',
            'value'=> 'CHtml::image(Yii::app()->baseUrl ."/data/icon/examBank/" . $data->icon, "", array("width"=>"200px" ,"height"=>"200px"))',
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
			'updateButtonUrl'=>'Yii::app()->controller->createUrl("/admin/examBank/update",array("id"=>$data->primaryKey))',
			'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/admin/examBank/delete",array("id"=>$data->primaryKey))',
			'buttons'=>array(
				'add_subject' => array(
					'label'=>'增加课程',
					'url'=>'Yii::app()->controller->createUrl("/admin/subject/create",array("exam_bank_id"=>$data->primaryKey))',
					'icon'=>'plus',
				),
			),
		),
	),
)); ?>
