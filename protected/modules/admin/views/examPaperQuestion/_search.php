<div class="wide form" style="margin-top:20px;padding:20px 20px;background-color:#EEEEEE">

<?php
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'question-filter-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'method'=>'post',
)); 
?>
	
	<div class="row" style="padding-left:30px;">
		<?php  echo $form->dropDownListRow($questionFilterForm, 'questionType', $questionTypes, array('class'=>'span5', 'empty'=>'全部')); ?>
	</div>
	
	<div class="row" style="padding-left:30px;">
		<?php  echo $form->dropDownListRow($questionFilterForm, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'全部')); ?>
	</div>
	
	<div class="row" style="padding-left:30px">
		<?php  echo $form->dropDownListRow($questionFilterForm, 'examPoints', $examPointListData, array('class'=>'span5','multiple'=>true)); ?>
	</div>
	
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'筛选',
		'htmlOptions' => array('style' => 'width:100px;margin-top:10px'),
	)); ?>

<?php $this->endWidget(); ?>
</div><!-- search-form -->

