<?php

/**
 * This is the model class for table "pay_record".
 *
 * The followings are the available columns in table 'pay_record':
 * @property integer $payment_record_id
 * @property integer $user_id
 * @property integer $exam_bank_id
 * @property double $money
 *
 * The followings are the available model relations:
 * @property User $user
 * @property ExamBank $examBank
 */
class PayRecordModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pay_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, exam_bank_id', 'required'),
			array('user_id, exam_bank_id', 'numerical', 'integerOnly'=>true),
			array('money', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('payment_record_id, user_id, exam_bank_id, money', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'examBank' => array(self::BELONGS_TO, 'ExamBank', 'exam_bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'payment_record_id' => 'Payment Record',
			'user_id' => 'User',
			'exam_bank_id' => 'Exam Bank',
			'money' => 'Money',
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

		$criteria->compare('payment_record_id',$this->payment_record_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('exam_bank_id',$this->exam_bank_id);
		$criteria->compare('money',$this->money);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PayRecordModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
