<?php
// subject create

$this->breadcrumbs=array(
	'创建课程',
);

$this->menu=array(
);
?>

<h1>创建"<?php echo $examBankModel->name;?>"课程</h1>

<?php include '_form.php';?>
<?php //echo $this->renderPartial('_form', array('model'=>$model)); ?>