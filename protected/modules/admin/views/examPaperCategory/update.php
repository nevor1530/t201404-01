<?php
$this->breadcrumbs=array(
	'Exam Paper Category Models'=>array('index'),
	$model->exam_paper_category_id=>array('view','id'=>$model->exam_paper_category_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ExamPaperCategoryModel','url'=>array('index')),
	array('label'=>'Create ExamPaperCategoryModel','url'=>array('create')),
	array('label'=>'View ExamPaperCategoryModel','url'=>array('view','id'=>$model->exam_paper_category_id)),
	array('label'=>'Manage ExamPaperCategoryModel','url'=>array('admin')),
);
?>

<h1>Update ExamPaperCategoryModel <?php echo $model->exam_paper_category_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>