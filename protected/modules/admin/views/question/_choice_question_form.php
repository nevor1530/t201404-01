<?php
	Yii::app()->umeditor->register();
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'choice-question-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	));
	
	$answerOptionCount = 0;
	if (isset($questionAnswerOptions) && count($questionAnswerOptions) > 0) {
		$answerOptionCount = count($questionAnswerOptions);
	}
?>
	
<div class="row" style="padding-left:30px;padding-top:20px">
	<?php  echo $form->dropDownListRow($choiceQuestionForm, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'全部')); ?>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php echo $form->textFieldRow($choiceQuestionForm,'questionNumber',array('class'=>'span5')); ?>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php echo $form->radioButtonListInlineRow($choiceQuestionForm, 'questionType', $choiceQuestionTypes); ?>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php echo $form->labelEx($choiceQuestionForm, 'content'); ?>
	<?php $this->widget('umeditor.widgets.UMeditorField', array(
		'model'=>$choiceQuestionForm,
		'name'=>'content',
		'width' => '800px',
		'height' => '150px'
	)); ?>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<div style="width:90px">
		<?php echo $form->labelEx($choiceQuestionForm, 'questionAnswerOptions'); ?>
	</div>
	<?php $this->widget('umeditor.widgets.UMeditorField', array(
		'model'=>$choiceQuestionForm,
		'name'=>'questionAnswerOptions',
		'width' => '800px',
		'height' => '150px'
	)); ?>
	<div>
		<button class="btn" type="button" style="margin-top:10px" onclick="saveAnswerOption()">保存选项</button>
		<button id="delete_options_btn" class="btn" type="button" style="<?php if ($answerOptionCount==0) { echo 'display:none';} ?>;margin-top:10px;margin-left:20px" onclick="deleteAllAnswerOptions()">删除所有选项</button>
	</div>
</div>

<div id="answerOptions" class="row" style="padding-left:30px;padding-top:20px">
<?php 
if ($answerOptionCount > 0) {
	foreach ($questionAnswerOptions as $questionAnswerOption) {
?>
<div id="questionAnswerOption<?php echo $index;?>" name="ChoiceQuestionForm[answerOption<?php echo $questionAnswerOption['index'];?>]">
	<div style="float:left"><?php echo chr($questionAnswerOption['index'] + 65); ?>. </div>
	<div><?php echo $questionAnswerOption['description'];?></div>
</div>
<?php }} ?>
</div>

<!-- hide fields -->
<div id="hiddenField">
<?php 
if ($answerOptionCount > 0) {
	foreach ($questionAnswerOptions as $questionAnswerOption) {
?>
<input type="hidden" name="ChoiceQuestionForm[answerOption<?php echo $questionAnswerOption['index'];?>]" value="<?php echo $questionAnswerOption['description'];?>">
<?php }}?>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php echo $form->labelEx($choiceQuestionForm, 'answer'); ?>
	<div id="correctAnswer">
		<?php 
		if ($answerOptionCount > 0) {
			foreach ($questionAnswerOptions as $questionAnswerOption) {
				$isCorrectAnswer = $questionAnswerOption['isCorrectAnswer'];
				// 单选题
				if ($choiceQuestionForm->questionType == 0) {
		?>
				<label class="radio inline" style="margin-right: 10px;">
					<input <?php if ($isCorrectAnswer) echo "checked=\"checked\""?> type="radio" name="ChoiceQuestionForm[answer]" value="<?php echo $questionAnswerOption['index'];?>">
					<label><?php echo chr($questionAnswerOption['index'] + 65); ?></label>
				</label>
		<?php } else { ?>
				<label class="checkbox inline" style="margin-right: 10px;">
					<input <?php if ($isCorrectAnswer) echo "checked"?> type="checkbox" name="ChoiceQuestionForm[answer][]" value="<?php echo $questionAnswerOption['index'];?>">
					<label><?php echo chr($questionAnswerOption['index'] + 65); ?></label>
				</label>
		<?php }}} ?>		
	</div>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php  echo $form->dropDownListRow($choiceQuestionForm, 'examPoints', $examPointListData, array('class'=>'span5','multiple'=>true)); ?>
</div>

<div class="row" style="padding-left:30px;padding-top:20px">
	<?php echo $form->labelEx($choiceQuestionForm, 'analysis'); ?>
	<?php $this->widget('umeditor.widgets.UMeditorField', array(
		'model'=>$choiceQuestionForm,
		'name'=>'analysis',
		'width' => '800px',
		'height' => '150px'
	)); ?>
</div>

<div class="form-actions">
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=>'submit',
	'type'=>'primary',
	'label'=>$isNewRecord ? '创建' : '修改',
)); ?>
</div>
	
<?php $this->endWidget(); ?>

<script type="text/javascript">
function createAnswerOptionDiv(number, content) {
	var parentDiv = $('<div></div>'); 
	parentDiv.attr('id','questionAnswerOption' + number);
	parentDiv.attr('name', 'ChoiceQuestionForm[answerOption' + number + ']'); 
	
	var numberDiv = $('<div></div>');
	numberDiv.css('float', 'left');
	numberDiv.html(String.fromCharCode(number + 65) + '. ');
	parentDiv.append(numberDiv);
	
	var contentDiv = $('<div></div>');
	contentDiv[0].innerHTML = content;
	parentDiv.append(contentDiv);
	
	return parentDiv;
}

function createCorrectAnswerDiv(answerOptionIndex) {
	$('#correctAnswer').empty();
	
	var questionType = $('input[type="radio"][name="ChoiceQuestionForm[questionType]"]:checked').val();
	
	// single choice type
	if (questionType == 0) {
		for (var i = 0; i <= answerOptionIndex; i++) {
			var parentElement = $('<label></label>'); 
			parentElement.addClass('radio');
			parentElement.addClass('inline');
			parentElement.css('margin-right', '10px');
	
			$('<input />', {type:"radio", name:"ChoiceQuestionForm[answer]", val:i}).appendTo(parentElement);
	
			var label = $('<label></label>'); 	
			label.html(String.fromCharCode(i + 65));
			parentElement.append(label);
	
			$('#correctAnswer').append(parentElement);
		}
	} else {
		for (var i = 0; i <= answerOptionIndex; i++) {
			var parentElement = $('<label></label>'); 
			parentElement.addClass('checkbox');
			parentElement.addClass('inline');
			parentElement.css('margin-right', '10px');
	
			$('<input />', {type:"checkbox", name:"ChoiceQuestionForm[answer][]", val:i}).appendTo(parentElement);
	
			var label = $('<label></label>'); 	
			label.html(String.fromCharCode(i + 65));
			parentElement.append(label);
	
			$('#correctAnswer').append(parentElement);
		}
	}
}

var answerOptionUM = UM.getEditor('ChoiceQuestionForm_questionAnswerOptions');
var answerOptionCount = <?php echo $answerOptionCount; ?>;
function saveAnswerOption() {
	var content = answerOptionUM.getContent();
	content.replace(/(^\s*)|(\s*$)/g, "");
	if (content.length <= 0) {
		return;
	}
	
	var answerOptionDiv = createAnswerOptionDiv(answerOptionCount, content);
	$('#answerOptions').append(answerOptionDiv);
	createCorrectAnswerDiv(answerOptionCount);
	answerOptionUM.setContent("");
	$('<input />', {type:'hidden', name:'ChoiceQuestionForm[answerOption' + answerOptionCount + ']', val: content}).appendTo('#hiddenField');
	$('#delete_options_btn').show();
	answerOptionCount++;
}

$('input[type="radio"][name="ChoiceQuestionForm[questionType]"]').click(function() {
	createCorrectAnswerDiv(answerOptionCount - 1);
});

function deleteAllAnswerOptions() {
	answerOptionCount = 0;
	$('#answerOptions').empty();
	$('#correctAnswer').empty();
	$('#delete_options_btn').hide();
	$('#hiddenField').empty();
}
</script>
