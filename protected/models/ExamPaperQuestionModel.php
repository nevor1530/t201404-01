<?php

/**
 * This is the model class for table "exam_paper_question".
 *
 * The followings are the available columns in table 'exam_paper_question':
 * @property integer $exam_paper_question_id
 * @property integer $exam_paper_id
 * @property integer $question_block_id
 * @property integer $question_id
 * @property integer $status
 * @property integer $sequence
 *
 * The followings are the available model relations:
 * @property ExamPaper $examPaper
 * @property Question $question
 * @property QuestionBlock $questionBlock
 */
class ExamPaperQuestionModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_paper_question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exam_paper_id, question_block_id, question_id, sequence', 'required'),
			array('exam_paper_id, question_block_id, question_id, status, sequence', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_paper_question_id, exam_paper_id, question_block_id, question_id, status, sequence', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'examPaper' => array(self::BELONGS_TO, 'ExamPaperModel', 'exam_paper_id'),
			'question' => array(self::BELONGS_TO, 'QuestionModel', 'question_id'),
			'questionBlock' => array(self::BELONGS_TO, 'QuestionBlockModel', 'question_block_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'exam_paper_question_id' => 'Exam Paper Question',
			'exam_paper_id' => 'Exam Paper',
			'question_block_id' => 'Question Block',
			'question_id' => 'Question',
			'status' => 'Status',
			'sequence' => 'Sequence',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('exam_paper_question_id',$this->exam_paper_question_id);
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('question_block_id',$this->question_block_id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('sequence',$this->sequence);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPaperQuestionModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
