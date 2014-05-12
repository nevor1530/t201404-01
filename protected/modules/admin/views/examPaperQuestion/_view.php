<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_paper_question_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->exam_paper_question_id),array('view','id'=>$data->exam_paper_question_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exam_paper_id')); ?>:</b>
	<?php echo CHtml::encode($data->exam_paper_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('question_block_id')); ?>:</b>
	<?php echo CHtml::encode($data->question_block_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('question_id')); ?>:</b>
	<?php echo CHtml::encode($data->question_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sequence')); ?>:</b>
	<?php echo CHtml::encode($data->sequence); ?>
	<br />


</div>