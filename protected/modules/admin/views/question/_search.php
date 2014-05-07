<div class="wide form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($model, 'exam_paper_id', $examPaperListData, array('class'=>'span5', 'empty'=>'全部')); ?>
	</div>
	

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'筛选',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->

