<?php
/* @var $this ExamBankController */
/* @var $model ExamBankModel */

$this->breadcrumbs=array(
	'创建题库',
);

$this->menu=array(
);
?>

<h1>创建题库</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>