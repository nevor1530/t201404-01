<?php
/* @var $this PayRecordController */
/* @var $data PayRecordModel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_record_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->payment_record_id), array('view', 'id'=>$data->payment_record_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_bank_id')); ?>:</b>
	<?php echo CHtml::encode($data->exam_bank_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('money')); ?>:</b>
	<?php echo CHtml::encode($data->money); ?>
	<br />


</div>