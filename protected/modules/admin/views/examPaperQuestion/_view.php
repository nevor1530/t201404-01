<?php if ($data->question->material_id > 0 && $data->question->material_id != $GLOBALS['prev_material_id']):
			$GLOBALS['prev_material_id'] = $data->question->material_id;?>
	<div style="margin-top:40px;">
		<div class="exam_paper-question-item-title" style="margin-bottom:5px;">
			<span style="font-size:20px;">材料预览：</span>
			<span class="exam-paper-question-sequence js-sequence-text-block <?php echo $data->sequence > 0 ? '' : 'hidden'?>">
				第<span class="js-sequence-text"><?php echo $data->globalSequence;?></span>道｜<button class="btn btn-mini btn-primary js-sequence-modify-btn" type="button">修改</button>
			</span>
			<span class="exam-paper-question-sequence js-sequence-input-block <?php echo $data->sequence == 0 ? '' : 'hidden'?>" >
				第<?php echo CHtml::numberField('', '',array('class'=>'js-sequence-input sequence-input', 'data-material_id'=>$data->question->material_id));?>道｜	<button class="btn btn-mini btn-primary js-sequence-btn" type="button">确定</button>
			</span>
			<span class="pull-right <?php echo $data->sequence == 0 ? '' : 'hidden'?>"><?php echo CHtml::link('从本试卷中踢出', Yii::app()->createUrl('/admin/examPaperQuestion/delete', array('exam_paper_id'=>$data->exam_paper_id, 'material_id'=>$data->question->material_id)), array('class'=>'js-exam-paper-question-delete'))?></span>
			<span class="pull-right <?php echo $data->sequence > 0 ? '' : 'hidden'?>"><?php echo CHtml::link('移除题号', Yii::app()->createUrl('/admin/examPaperQuestion/sequence', array('exam_paper_id'=>$data->exam_paper_id, 'material_id'=>$data->question->material_id)), array('class'=>'js-exam-paper-question-unsequence'))?></span>
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
			<span class="exam-paper-question-sequence js-sequence-text-block <?php echo $data->sequence > 0 ? '' : 'hidden'?>">
				第<span class="js-sequence-text"><?php echo $data->globalSequence;?></span>道｜<button class="btn btn-mini btn-primary js-sequence-modify-btn" type="button">修改</button>
			</span>
			<span class="exam-paper-question-sequence js-sequence-input-block <?php echo $data->sequence == 0 ? '' : 'hidden'?>" >
				第<?php echo CHtml::numberField('', '',array('class'=>'js-sequence-input sequence-input', 'data-question_id'=>$data->question->question_id));?>道｜	<button class="btn btn-mini btn-primary js-sequence-btn" type="button">确定</button>
			</span>
			<span class="pull-right <?php echo $data->sequence == 0 ? '' : 'hidden'?>"><?php echo CHtml::link('从本试卷中踢出', Yii::app()->createUrl('/admin/examPaperQuestion/delete', array('exam_paper_id'=>$data->exam_paper_id, 'question_id'=>$data->question_id)), array('class'=>'js-exam-paper-question-delete'))?></span>
			<span class="pull-right <?php echo $data->sequence > 0 ? '' : 'hidden'?>"><?php echo CHtml::link('移除题号', Yii::app()->createUrl('/admin/examPaperQuestion/sequence', array('exam_paper_id'=>$data->exam_paper_id, 'question_id'=>$data->question_id)), array('class'=>'js-exam-paper-question-unsequence'))?></span>
		<?php endif;?>
	</div>
	
	<b><?php echo QuestionModel::$QUESTION_TYPES[$data->question->question_type]; ?>:</b>
	<?php echo $data->question->questionExtra->title;?>
	
	<?php if ($data->question->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $data->question->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE):?>
		<div class="row" style="padding-left:30px;padding-top:10px">
			<div style="padding-bottom:10px;">选项：  </div>
			<div>
				<?php foreach ($data->question->questionAnswerOptions as $answerOption) {?>
					<div style="float:left"><?php echo chr($answerOption->index + ord('A')) . ". "?></div>
					<div><?php echo $answerOption->description;?></div>
				<?php }?>
			</div>
		</div>
	<?php endif;?>
</div>