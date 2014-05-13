<div class="view" id="<?php echo 'question-'.$data->question_id?>">
	<div class="exam_paper-question-item-title">
		<span>#<?php echo $data->question_id;?></span>
		<span class="exam-paper-question-sequence js-sequence <?php echo $data->sequence > 0 ? '' : 'hidden'?>">第<span "js-sequence-text"><?php echo $data->globalSequence;?></span>道｜<button class="btn btn-mini btn-primary" type="button">修改</button></span>
		<span class="exam-paper-question-sequence js-sequence-input <?php echo $data->sequence == 0 ? '' : 'hidden'?>" >
			第<?php echo CHtml::numberField('', '',array('class'=>'sequence-input', 'data-id'=>$data->primaryKey));?>道｜<button class="btn btn-mini btn-primary js-sequence-btn" type="button">确定</button>
		</span>
		<span class="pull-right <?php echo $data->sequence == 0 ? '' : 'hidden'?>"><?php echo CHtml::link('从本试卷中踢出', Yii::app()->createUrl('/admin/examPaperQuestion/delete', array('id'=>$data->primaryKey)), array('class'=>'js-exam-paper-question-delete'))?></span>
	</div>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('question.questionExtra.title')); ?>:</b>
	<?php echo $data->question->questionExtra->title;?>
	<br />
</div>