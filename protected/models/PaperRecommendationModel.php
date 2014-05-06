<?php

/**
 * This is the model class for table "paper_recommendation".
 *
 * The followings are the available columns in table 'paper_recommendation':
 * @property integer $paper_recommendation_id
 * @property integer $subject_id
 * @property integer $exam_paper_id
 * @property integer $order
 * @property double $difficuty
 *
 * The followings are the available model relations:
 * @property Subject $subject
 * @property ExamPaper $exampPaper
 */
class PaperRecommendationModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'paper_recommendation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject_id, exam_paper_id', 'required'),
			array('subject_id, exam_paper_id, order', 'numerical', 'integerOnly'=>true),
			array('difficuty', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('paper_recommendation_id, subject_id, exam_paper_id, order, difficuty', 'safe', 'on'=>'search'),
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
			'subject' => array(self::BELONGS_TO, 'Subject', 'subject_id'),
			'exampPaper' => array(self::BELONGS_TO, 'ExamPaper', 'exam_paper_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'paper_recommendation_id' => 'Paper Recommendation',
			'subject_id' => 'Subject',
			'exam_paper_id' => 'Examp Paper',
			'order' => 'Order',
			'difficuty' => 'Difficuty',
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

		$criteria->compare('paper_recommendation_id',$this->paper_recommendation_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('order',$this->order);
		$criteria->compare('difficuty',$this->difficuty);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaperRecommendationModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
