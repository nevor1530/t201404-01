<?php
/**
 * TrueOrFalseQuestionForm class.
 * TrueOrFalseQuestionForm is the data structure for keeping
 * true or false question form data. It is used by the 'createTrueOrFalseQuestion' action of 'QuestionController'.
 */
class TrueOrFalseQuestionForm extends CFormModel
{
	public $examPaper;
	public $questionNumber;
	public $content;
	public $answer;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('content, answer', 'required'),
			array('examPaper, questionNumber', 'safe'),
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
			'content' => '题干',
			'answer' => '正确答案',
		);
	}
	
}
