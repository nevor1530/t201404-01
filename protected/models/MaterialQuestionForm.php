<?php
/**
 * MaterialQuestionForm class.
 * MaterialQuestionForm is the data structure for keeping
 * material question form data. It is used by the 'createMaterialQuestion' action of 'QuestionController'.
 */
class MaterialQuestionForm extends CFormModel
{
	public $examPaper;
	public $content;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('content', 'required'),
			array('examPaper, content', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'examPaper' => '所属试卷',
			'content' => '题干',
		);
	}

}
