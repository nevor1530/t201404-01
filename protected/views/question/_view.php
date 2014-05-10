<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('question_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->question_id),array('view','id'=>$data->question_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_paper_id')); ?>:</b>
	<?php echo CHtml::encode($data->exam_paper_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('question_block_id')); ?>:</b>
	<?php echo CHtml::encode($data->question_block_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('material_id')); ?>:</b>
	<?php echo CHtml::encode($data->material_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('index')); ?>:</b>
	<?php echo CHtml::encode($data->index); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('answer')); ?>:</b>
	<?php echo CHtml::encode($data->answer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('question_type')); ?>:</b>
	<?php echo CHtml::encode($data->question_type); ?>
	<br />


</div>