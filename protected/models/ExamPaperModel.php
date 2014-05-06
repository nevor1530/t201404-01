<?php

/**
 * This is the model class for table "exam_paper".
 *
 * The followings are the available columns in table 'exam_paper':
 * @property integer $exam_paper_id
 * @property integer $subject_id
 * @property string $name
 * @property string $short_name
 * @property integer $score
 * @property integer $recommendation
 * @property integer $category_id
 * @property integer $time_length
 * @property integer $sequence
 * @property string $publish_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property Subject $subject
 * @property ExamPaperInstance[] $examPaperInstances
 * @property PaperRecommendation[] $paperRecommendations
 * @property Question[] $questions
 * @property QuestionType[] $questionTypes
 */
class ExamPaperModel extends CActiveRecord
{
	public $_question_number = null;
	
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
			array('subject_id, score, recommendation, category_id, time_length, sequence, status', 'numerical', 'integerOnly'=>true),
			array('name, short_name', 'length', 'max'=>45),
			array('publish_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_paper_id, subject_id, name, short_name, score, recommendation, category_id, time_length, sequence, publish_time, status', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'subject' => array(self::BELONGS_TO, 'Subject', 'subject_id'),
			'examPaperInstances' => array(self::HAS_MANY, 'ExamPaperInstance', 'exam_paper_id'),
			'paperRecommendations' => array(self::HAS_MANY, 'PaperRecommendation', 'examp_paper_id'),
			'questions' => array(self::HAS_MANY, 'Question', 'exam_paper_id'),
			'questionTypes' => array(self::HAS_MANY, 'QuestionType', 'examp_paper_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'exam_paper_id' => 'ID',
			'name' => '试卷名称',
			'short_name' => '简称',
			'score' => '总分',
			'recommendation' => '推荐值',
			'category_id' => '分类ID',
			'time_length' => '答卷时间',
			'publish_time' => '试卷年份',
			'status' => '状态',
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

		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('recommendation',$this->recommendation);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('time_length',$this->time_length);
		$criteria->compare('sequence',$this->sequence);
		$criteria->compare('publish_time',$this->publish_time,true);
		$criteria->compare('status',$this->status);

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
	
	public function getQuestionNumber(){
		if ($this->_question_number === null){
			
		}
		return $this->_question_number;
	}
}
