<?php

/**
 * This is the model class for table "exam_point".
 *
 * The followings are the available columns in table 'exam_point':
 * @property integer $exam_point_id
 * @property string $name
 * @property integer $pid
 *
 * The followings are the available model relations:
 * @property QuestionExamPoint[] $questionExamPoints
 * @property Subject[] $subjects
 */
class ExamPointModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_point';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('pid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_point_id, name, pid', 'safe', 'on'=>'search'),
		);
	}
		
	public function scopes()
	{
		return array(
			'top'=>array(
				'condition'=>'pid=0',
			)
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
			'questionExamPoints' => array(self::HAS_MANY, 'QuestionExamPoint', 'exam_point_id'),
			'subjects' => array(self::HAS_MANY, 'Subject', 'exam_point_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'exam_point_id' => 'ID',
			'name' => '考点树名称',
			'pid' => '父级ID',
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

		$criteria->compare('exam_point_id',$this->exam_point_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pid',$this->pid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPointModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
