<?php
/* @var $this ExamBankController */
/* @var $data ExamBankModel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_bank_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->exam_bank_id), array('view', 'id'=>$data->exam_bank_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />


</div>