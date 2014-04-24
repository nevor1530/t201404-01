<?php
$this->breadcrumbs=array(
	'题库管理'=>array('index'),
	'创建题库',
);

$this->menu=array(
);
?>

<h1>创建题库</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>