<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
	'添加材料题',
);
?>

<?php
	Yii::app()->umeditor->register();
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'true-false-question-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); 
?>

<div class="wide form">
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($materialQuestionForm, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'暂不指定试卷')); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($materialQuestionForm, 'content'); ?>
		<?php $this->widget('umeditor.widgets.UMeditorField', array(
			'model'=>$materialQuestionForm,
			'name'=>'content',
			'width' => '800px',
			'height' => '150px'
		)); ?>
	</div>
	
	<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'提交'
	)); ?>
	</div>
	
<?php $this->endWidget(); ?>
</div><!-- search-form -->
