<?php
/* @var $this PaymentController */
/* @var $data PaymentModel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->payment_id), array('view', 'id'=>$data->payment_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_bank_id')); ?>:</b>
	<?php echo CHtml::encode($data->exam_bank_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expiry')); ?>:</b>
	<?php echo CHtml::encode($data->expiry); ?>
	<br />


</div>