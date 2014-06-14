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
	const REAL_EXAM_PAPER_TYPE = 0;
	const NORMAL_PRACTISE_TYPE = 1;
	const WRONG_QUESTION_PRACTISE_TYPE = 2;
	const FAVORITE_QUESTION_PRACTISE_TYPE = 3;
	
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
			array('user_id, start_time, elapsed_time, is_completed', 'required'),
			array('exam_paper_id, instance_type, exam_bank_id, subject_id, exam_point_id, user_id, elapsed_time, is_completed', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_paper_instance_id, instance_type, exam_bank_id, subject_id, exam_paper_id, exam_point_id, user_id, start_time, elapsed_time, is_completed', 'safe', 'on'=>'search'),
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
			'instance_type' => '试卷类型',
			'exam_paper_id' => '如果试卷是随机生成的，则0',
			'exam_point_id' => '如果试卷不是随机生成的，则0',
			'user_id' => 'User',
			'start_time' => 'Start Time',
			'elapsed_time' => '所耗时间',
			'is_completed' => '是否已交卷'
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
		$criteria->compare('instance_type',$this->instance_type);
		$criteria->compare('exam_bank_id',$this->exam_bank_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('exam_point_id',$this->exam_point_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('elapsed_time',$this->elapsed_time);
		$criteria->compare('is_completed',$this->is_completed);

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
