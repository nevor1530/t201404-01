<?php
$this->breadcrumbs=array(
	'创建题库',
);

$this->menu=array(
);
?>

<h1>创建题库</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>