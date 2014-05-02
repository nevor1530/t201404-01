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
 */
class QuestionModel extends CActiveRecord
{
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
			array('exam_paper_id, index, answer', 'required'),
			array('exam_paper_id, question_type_id, material_id, index, is_multiple, answer', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_id, exam_paper_id, question_type_id, material_id, index, is_multiple, answer', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'question_id' => 'Question',
			'exam_paper_id' => 'Exam Paper',
			'question_type_id' => 'Question Type',
			'material_id' => 'Material',
			'index' => 'Index',
			'is_multiple' => 'Is Multiple',
			'answer' => 'Answer',
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
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('question_type_id',$this->question_type_id);
		$criteria->compare('material_id',$this->material_id);
		$criteria->compare('index',$this->index);
		$criteria->compare('is_multiple',$this->is_multiple);
		$criteria->compare('answer',$this->answer);

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
