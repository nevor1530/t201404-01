<?php
/* @var $this SubjectController */
/* @var $data SubjectModel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('subject_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->subject_id), array('view', 'id'=>$data->subject_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_bank_id')); ?>:</b>
	<?php echo CHtml::encode($data->exam_bank_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_point_id')); ?>:</b>
	<?php echo CHtml::encode($data->exam_point_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>