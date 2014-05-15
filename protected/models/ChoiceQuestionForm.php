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
	public $questionAnswerOptions;
	public $answer;
	public $examPoints;
	public $analysis;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('questionType, content, answer', 'required'),
			array('examPaper, questionNumber', 'numerical', 'integerOnly'=>true),
			array('examPaper, questionNumber, examPoints, analysis', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'examPaper' => '所属试卷',
			'questionNumber' => '试卷中题号（数字）',
			'questionType' => '题型',
			'content' => '题干',
			'questionAnswerOptions' => '选项',
			'answer' => '正确答案',
			'examPoints' => '考点',
			'analysis' => '解析'
		);
	}

}
