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
					<button class="btn btn-mini btn-question btn-primary" type="button"><?php echo $i+$sequenceBase;?></button>
				<?php else:?>
					<button class="btn btn-mini btn-question" type="button"><?php echo $i+$sequenceBase;?></button>
				<?php endif;?>
			<?php endfor;?>
		</p>
		<?php $sequenceBase += $questionBlockModel->question_number;?>
	<?php endforeach;?>
</div>

<h4>备选题</h4>
<?php 
$GLOBALS['prev_material_id'] = 0;
$this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$questionDataProvider,
	'itemView'=>'_view',
)); ?>

<?php 
$cssScript = <<< END
.exam_paper-question-item-title {
	border-bottom: 1px dashed;
}

.btn-question {
	width: 25px;
	height: 25px;
	padding: 0;
}

input.sequence-input {
	height: 22px;
	margin: 0;
	padding: 0;
	width: 50px;
}

.exam-paper-question-sequence {
	margin-left: 40%;
}

.material_question {
	padding-left: 40px;
}
END;

Yii::app()->clientScript->registerCss(__CLASS__."#css", $cssScript);
?>

<?php
$sequenceUrl = Yii::app()->createUrl('/admin/examPaperQuestion/sequence');
$sequenceScript = <<< END
jQuery(".js-sequence-btn").on("click", function(){
	jQthis = jQuery(this);
	var input = jQthis.parent().find(".sequence-input");
	if (!/^\d+$/.test(input.val())){
	    alert('必须为数字');
	}
	
	var url = "$sequenceUrl";
	var data = {
		sequence: input.val(),
		exam_paper_id: $examPaperModel->primaryKey
	};
	if (input.data("question_id")){
		data.question_id = input.data("question_id");
	} else {
		data.material_id = input.data("material_id");
	}
	jQuery.ajax({
		url: "$sequenceUrl",
		data: data,
		type: "POST",
		dataType: 'json',
		success: function(data){
			if (data.status === 0){
				location.reload();
			} else {
				alert(data.errMsg);
			}
		}
	});
});
END;

Yii::app()->clientScript->registerScript(__CLASS__."#sequence", $sequenceScript);