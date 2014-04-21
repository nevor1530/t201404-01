<?php

/**
 * This is the model class for table "subject".
 *
 * The followings are the available columns in table 'subject':
 * @property integer $subject_id
 * @property integer $exam_bank_id
 * @property integer $exam_point_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property ExamPaper[] $examPapers
 * @property ExamBank $examBank
 * @property ExamPoint $examPoint
 */
class SubjectModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'subject';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exam_bank_id, exam_point_id, name', 'required'),
			array('exam_bank_id, exam_point_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('subject_id, exam_bank_id, exam_point_id, name', 'safe', 'on'=>'search'),
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
			'examPapers' => array(self::HAS_MANY, 'ExamPaper', 'subject_id'),
			'examBank' => array(self::BELONGS_TO, 'ExamBank', 'exam_bank_id'),
			'examPoint' => array(self::BELONGS_TO, 'ExamPoint', 'exam_point_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'subject_id' => 'Subject',
			'exam_bank_id' => 'Exam Bank',
			'exam_point_id' => 'Exam Point',
			'name' => 'Name',
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

		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('exam_bank_id',$this->exam_bank_id);
		$criteria->compare('exam_point_id',$this->exam_point_id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubjectModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
