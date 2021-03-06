<?php

/**
 * This is the model class for table "question".
 *
 * The followings are the available columns in table 'question':
 * @property integer $question_id
 * @property integer $exam_paper_id
 * @property integer $question_type_id
 * @property integer $material_id
 * @property integer $index
 * @property integer $is_multiple
 * @property integer $answer
 *
 * The followings are the available model relations:
 * @property Material $material
 * @property QuestionType $questionType
 * @property ExamPaper $examPaper
 * @property QuestionAnswerOption[] $questionAnswerOptions
 * @property QuestionExamPoint[] $questionExamPoints
 * @property QuestionExtra $questionExtra
 * @property QuestionInstance[] $questionInstances
 */
class QuestionModel extends CActiveRecord
{
	const SINGLE_CHOICE_TYPE = 0;
	const MULTIPLE_CHOICE_TYPE = 1;
	const TRUE_FALSE_TYPE = 2;
	const MATERIAL_TYPE = 3;
	
	public static $QUESTION_TYPES = array (	
			QuestionModel::SINGLE_CHOICE_TYPE => '单选题',
			QuestionModel::MULTIPLE_CHOICE_TYPE => '多选题',
			QuestionModel::TRUE_FALSE_TYPE => '判断题',
			QuestionModel::MATERIAL_TYPE => '材料题',
	);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('answer', 'required'),
			array('material_id, question_type, subject_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_id, material_id, answer, question_type, subject_id', 'safe', 'on'=>'search'),
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
			'material' => array(self::BELONGS_TO, 'MaterialModel', 'material_id'),
			'questionBlock' => array(self::BELONGS_TO, 'QuestionBlockModel', 'question_block_id'),
			'questionAnswerOptions' => array(self::HAS_MANY, 'QuestionAnswerOptionModel', 'question_id'),
			'questionExamPoints' => array(self::HAS_MANY, 'QuestionExamPointModel', 'question_id'),
			'questionExtra' => array(self::HAS_ONE, 'QuestionExtraModel', 'question_id'),
			'questionInstances' => array(self::HAS_MANY, 'QuestionInstanceModel', 'question_id'),
			'examPaperQuestions' => array(self::HAS_MANY, 'ExamPaperQuestionModel', 'question_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'question_id' => 'Question',
			'subject_id' => 'subject_id',
			'material_id' => 'Material',
			'answer' => 'Answer',
			'question_type' => 'question_type',
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

		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('material_id',$this->material_id);
		$criteria->compare('answer',$this->answer);
		$criteria->compare('question_type',$this->question_type);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->order = 'exam_paper_id desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuestionModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
