<?php
$this->breadcrumbs=array(
	'题库管理'=>array('index'),
	'更新题库',
);

$this->menu=array(
);
?>

<h1>更新题库 '<?php echo $model->name; ?>'</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>