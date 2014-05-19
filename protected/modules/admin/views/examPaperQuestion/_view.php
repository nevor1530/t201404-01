<?php if ($data->question->material_id > 0 && $data->question->material_id != $GLOBALS['prev_material_id']):
			$GLOBALS['prev_material_id'] = $data->question->material_id;?>
	<div style="margin-top:40px;">
		<div style="margin-bottom:5px;">
			<span style="font-size:20px;">材料预览：</span>
			<span class="exam-paper-question-sequence js-sequence <?php echo $data->sequence > 0 ? '' : 'hidden'?>">第<span "js-sequence-text"><?php echo $data->globalSequence;?></span>道｜<button class="btn btn-mini btn-primary js-sequence-modify-btn" type="button">修改</button></span>
			<span class="exam-paper-question-sequence js-sequence-input <?php echo $data->sequence == 0 ? '' : 'hidden'?>" >
				第<?php echo CHtml::numberField('', '',array('class'=>'sequence-input', 'data-material_id'=>$data->question->material_id));?>道｜<button class="btn btn-mini btn-primary js-sequence-btn" type="button">确定</button>
			</span>
			<span class="pull-right <?php echo $data->sequence == 0 ? '' : 'hidden'?>"><?php echo CHtml::link('从本试卷中踢出', Yii::app()->createUrl('/admin/examPaperQuestion/delete', array('id'=>$data->primaryKey)), array('class'=>'js-exam-paper-question-delete'))?></span>
		</div>
		<div>
			<div style="border-top:dashed 1px #000;"><?php echo $data->question->material->content; ?></div>
		</div>
	</div>
<?php endif;?>
<div class="view <?php echo $data->question->material_id > 0 ? 'material_question' : '';?>" id="<?php echo 'question-'.$data->question_id?>">
	<div class="exam_paper-question-item-title">
		<span>#<?php echo $data->question_id;?></span>
		<?php if($data->question->material_id == 0):?>
			<span class="exam-paper-question-sequence js-sequence <?php echo $data->sequence > 0 ? '' : 'hidden'?>">第<span "js-sequence-text"><?php echo $data->globalSequence;?></span>道｜<button class="btn btn-mini btn-primary" type="button">修改</button></span>
			<span class="exam-paper-question-sequence js-sequence-input <?php echo $data->sequence == 0 ? '' : 'hidden'?>" >
				第<?php echo CHtml::numberField('', '',array('class'=>'sequence-input', 'data-question_id'=>$data->question->question_id));?>道｜<button class="btn btn-mini btn-primary js-sequence-btn" type="button">确定</button>
			</span>
			<span class="pull-right <?php echo $data->sequence == 0 ? '' : 'hidden'?>"><?php echo CHtml::link('从本试卷中踢出', Yii::app()->createUrl('/admin/examPaperQuestion/delete', array('id'=>$data->primaryKey)), array('class'=>'js-exam-paper-question-delete'))?></span>
		<?php endif;?>
	</div>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('question.questionExtra.title')); ?>:</b>
	<?php echo $data->question->questionExtra->title;?>
	<br />
</div>