<?php
/**
 * ChoiceQuestionForm class.
 * ChoiceQuestionForm is the data structure for keeping
 * choice question form data. It is used by the 'createChoiceQuestion' action of 'QuestionController'.
 */
class ChoiceQuestionForm extends CFormModel
{
	public $examPaper;
	public $questionNumber;
	public $questionType;
	public $content;
	public $questionOptions;
	public $answer;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('questionType, content, questionAnswerOptions, answer', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'examPaper' => '所属试卷',
			'questionNumber' => '试卷中题号',
			'questionType' => '题型',
			'content' => '题干',
			'questionAnswerOptions' => '选项',
			'answer' => '正确答案',
		);
	}

	public function submit() {
	}
}
