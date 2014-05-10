<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'试题管理' => array('/admin/question/index', 'subject_id'=>$subject_id),
	'添加选择题',
);
?>

<?php
	Yii::app()->umeditor->register();
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'choice-question-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	));
?>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($choiceQuestionForm, 'examPaper', $examPaperListData, array('class'=>'span5', 'empty'=>'全部')); ?>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($choiceQuestionForm, 'questionNumber'); ?>
		<?php echo $form->textField($choiceQuestionForm, 'questionNumber'); ?>
		<?php echo $form->error($choiceQuestionForm, 'questionNumber'); ?>
	</div>
	
	<div>
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
			<button class="btn"  type="button" style="margin-top:10px" onclick="saveAnswerOption()">保存选项</button>
			<button id="delete_options_btn" class="btn"  type="button" style="display:none;margin-top:10px;margin-left:20px" onclick="deleteAllAnswerOptions()">删除所有选项</button>
		</div>
	</div>
	
	<div id="answerOptions" class="row" style="padding-left:30px;padding-top:20px">
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php echo $form->labelEx($choiceQuestionForm, 'answer'); ?>
		<div id="correctAnswer">
		</div>
	</div>
	
	<div class="row" style="padding-left:30px;padding-top:20px">
		<?php  echo $form->dropDownListRow($choiceQuestionForm, 'examPoints', $examPointListData, array('class'=>'span5','multiple'=>true)); ?>
	</div>
	
	<div id="hiddenField">
	</div>
	
	<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'提交'
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
var answerOptionCount = 0;
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
	$('<input />', {type:'hidden', name:'ChoiceQuestionForm[answerOption' + answerOptionCount + ']', val: content}).appendTo('#choice-question-form');
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

