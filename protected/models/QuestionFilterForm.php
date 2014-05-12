<?php
/**
 * QuestionFilterForm class.
 * QuestionFilterForm is the data structure for keeping
 * question filtering condition form data. It is used by the 'advancedSearch' action of 'QuestionController'.
 */
class QuestionFilterForm extends CFormModel
{
	public $questionType;
	public $examPaper;
	public $hasAnalysis;
	public $examPoints;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('examPaper, questionType, examPoints, hasAnalysis', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'questionType' => '题目类型',
			'examPaper' => '所属试卷',
			'hasAnalysis' => '解析状态',
			'examPoints' => '考点',
		);
	}

}
