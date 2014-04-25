<?php

/**
 * This is the model class for table "subject".
 *
 * The followings are the available columns in table 'subject':
 * @property integer $subject_id
 * @property integer $exam_bank_id
 * @property integer $exam_point_id
 * @property string $name
 * @property integer $exam_point_show_level
 *
 * The followings are the available model relations:
 * @property ExamPaper[] $examPapers
 * @property ExamBank $examBank
 * @property ExamPoint $examPoint
 */
class SubjectModel extends CActiveRecord
{
	public $exam_bank;
	public $exam_point;
	
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
			array('exam_bank_id, name', 'required'),
			array('exam_bank_id, exam_point_id, exam_point_show_level', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('exam_point_show_level', 'numerical', 'min'=>1, 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('subject_id, exam_bank, exam_point, name, exam_point_show_level', 'safe', 'on'=>'search'),
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
			'examPapers' => array(self::HAS_MANY, 'ExamPaperModel', 'subject_id'),
			'examBank' => array(self::BELONGS_TO, 'ExamBankModel', 'exam_bank_id'),
			'examPoint' => array(self::BELONGS_TO, 'ExamPointModel', 'exam_point_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'subject_id' => 'ID',
			'exam_bank_id' => '题库',
			'exam_bank' => '题库',
			'exam_point_id' => '考点树',
			'exam_point' => '考点树',
			'name' => '课程名称',
			'exam_point_show_level' => '考点树显示层级',
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
		$criteria->with = array('examBank', 'examPoint');

		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('examBank.name',$this->exam_bank, true);
		$criteria->compare('examPoint.name',$this->exam_point, true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('exam_point_show_level',$this->exam_point_show_level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
		        'attributes'=>array(
		            'exam_bank'=>array(
		                'asc'=>'examBank.name',
		                'desc'=>'examBank.name DESC',
		            ),
		            'exam_point'=>array(
		                'asc'=>'examPoint.name',
		                'desc'=>'examPoint.name DESC',
		            ),
		            '*',
		        ),
		    ),
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
