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
 * @property string $publish_time
 * @property integer $status
 * @property integer $is_real
 *
 * The followings are the available model relations:
 * @property ExamPaperQuestion[] $examPaperQuestions
 * @property Material[] $materials
 */
class ExamPaperModel extends CActiveRecord
{
	const STATUS_UNCOMPLETE = 0;
	const STATUS_UNPUBLISHED = 1;
	const STATUS_PUBLISHED = 2;
	
	public static $IS_REAL_MAP = array('0'=>'否', '1'=>'是');
	
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
			array('subject_id, score, recommendation, category_id, time_length, status, is_real', 'numerical', 'integerOnly'=>true),
			array('name, short_name', 'length', 'max'=>45),
			array('publish_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_paper_id, subject_id, name, short_name, score, recommendation, category_id, time_length, publish_time, status, is_real', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'CategoryModel', 'category_id'),
			'subject' => array(self::BELONGS_TO, 'SubjectModel', 'subject_id'),
			'examPaperInstances' => array(self::HAS_MANY, 'ExamPaperInstanceModel', 'exam_paper_id'),
			'paperRecommendations' => array(self::HAS_MANY, 'PaperRecommendationModel', 'examp_paper_id'),
			'questions' => array(self::HAS_MANY, 'QuestionModel', 'exam_paper_id'),
			'questionTypes' => array(self::HAS_MANY, 'QuestionTypeModel', 'examp_paper_id'),
			'examPaperQuestions' => array(self::HAS_MANY, 'ExamPaperQuestionModel', 'exam_paper_id'),
			'materials' => array(self::HAS_MANY, 'MaterialModel', 'exam_paper_id'),
		);
	}
	
	public function scopes()
	{
		return array(
			'real'=>array(
				'condition'=>'is_real=1',
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => '试卷名称',
			'short_name' => '简称',
			'score' => '总分',
			'recommendation' => '推荐值',
			'category_id' => '分类ID',
			'time_length' => '答卷时间(分钟)',
			'publish_time' => '试卷年份',
			'status' => '状态',
			'is_real'=> '是否真卷',
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
		$criteria->compare('publish_time',$this->publish_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_real',$this->is_real);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPaperModel2 the static model class
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
	
	protected function beforeDelete()
	{
		$questionBlockModels = $this->questionBlocks;
		if ($questionBlockModels){
			foreach($questionBlockModels as $questionBlockModel){
				$questionBlockModel->delete();
			}
		}
			
		return parent::beforeDelete();
	}
}
