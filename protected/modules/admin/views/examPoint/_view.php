<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_point_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->exam_point_id),array('view','id'=>$data->exam_point_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::encode($data->pid); ?>
	<br />


</div>