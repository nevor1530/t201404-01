<?php

/**
 * This is the model class for table "exam_paper".
 *
 * The followings are the available columns in table 'exam_paper':
 * @property integer $examp_paper_id
 * @property integer $subject_id
 * @property string $name
 * @property string $short_name
 * @property integer $score
 * @property integer $recommendation
 * @property integer $category_id
 * @property integer $time_length
 */
class ExamPaperModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_paper';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject_id, name', 'required'),
			array('subject_id, score, recommendation, category_id, time_length', 'numerical', 'integerOnly'=>true),
			array('name, short_name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('examp_paper_id, subject_id, name, short_name, score, recommendation, category_id, time_length', 'safe', 'on'=>'search'),
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
			'examp_paper_id' => 'Examp Paper',
			'subject_id' => 'Subject',
			'name' => 'Name',
			'short_name' => 'Short Name',
			'score' => 'Score',
			'recommendation' => 'Recommendation',
			'category_id' => 'Category',
			'time_length' => 'Time Length',
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

		$criteria->compare('examp_paper_id',$this->examp_paper_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('recommendation',$this->recommendation);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('time_length',$this->time_length);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPaperModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
