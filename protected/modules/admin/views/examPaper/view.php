<?php
$paperName = $model->short_name ? $model->short_name : $model->name;

$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试卷管理'=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	$paperName,
);

$this->menu=array(
	array('label'=>'List ExamPaperModel','url'=>array('index')),
	array('label'=>'Create ExamPaperModel','url'=>array('create')),
	array('label'=>'Update ExamPaperModel','url'=>array('update','id'=>$model->exam_paper_id)),
	array('label'=>'Delete ExamPaperModel','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->exam_paper_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ExamPaperModel','url'=>array('admin')),
);
?>

<h1>View ExamPaperModel #<?php echo $model->exam_paper_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'exam_paper_id',
		'subject_id',
		'name',
		'short_name',
		'score',
		'recommendation',
		'category_id',
		'time_length',
		'order',
	),
)); ?>
