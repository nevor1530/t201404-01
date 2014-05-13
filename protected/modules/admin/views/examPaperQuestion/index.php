<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	$examPaperModel->name=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->primaryKey),
	'题目管理',
);

$this->menu=array(
	array('label'=>'从试题库选题','url'=>array('choose', 'exam_paper_id'=>$examPaperModel->primaryKey)),
);

?>

<h3><?php echo $examPaperModel->name;?> <?php echo $paperQuestionAssignNumber.'/'.$paperQuestionNumber;?></h3>

<div class="well">
	<?php 
	$sequenceBase = 0;	// 题号计数
	foreach($questionBlockModels as $questionBlockModel):?>
		<p>
			<div><strong><?php echo $questionBlockModel->name;?> 模块</strong></div>
			<?php for($i=1; $i <= $questionBlockModel->question_number; $i++):?>
				<?php if (isset($questionStatus[$questionBlockModel->primaryKey][$i])):?>
					<button class="btn btn-mini btn-primary" type="button"><?php echo $i+$sequenceBase;?></button>
				<?php else:?>
					<button class="btn btn-mini" type="button"><?php echo $i+$sequenceBase;?></button>
				<?php endif;?>
			<?php endfor;?>
		</p>
		<?php $sequenceBase += $questionBlockModel->question_number;?>
	<?php endforeach;?>
</div>

<h4>备选题</h4>
<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$unAssignedQuestionDataProvider,
	'itemView'=>'_view',
)); ?>

<?php 
	$cssScript = <<< END
.btn-mini {
	width: 25px;
	height: 25px;
	padding: 0;
}
END;
	Yii::app()->clientScript->registerCss(__CLASS__."#css", $cssScript);
?>
