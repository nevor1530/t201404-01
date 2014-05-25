<?php

/**
 * This is the model class for table "exam_paper_instance".
 *
 * The followings are the available columns in table 'exam_paper_instance':
 * @property integer $exam_paper_instance_id
 * @property integer $exam_paper_id
 * @property integer $user_id
 * @property string $start_time
 * @property integer $remain_time
 */
class ExamPaperInstanceModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_paper_instance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, start_time, remain_time', 'required'),
			array('exam_paper_id, user_id, remain_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_paper_instance_id, exam_paper_id, user_id, start_time, remain_time', 'safe', 'on'=>'search'),
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
			'exam_paper_instance_id' => '生成的卷子实例，即用户做了的',
			'exam_paper_id' => '如果试卷是随机生成的，则0',
			'user_id' => 'User',
			'start_time' => 'Start Time',
			'remain_time' => '剩余时间',
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

		$criteria->compare('exam_paper_instance_id',$this->exam_paper_instance_id);
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('remain_time',$this->remain_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPaperInstanceModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
