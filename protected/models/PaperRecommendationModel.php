<?php

/**
 * This is the model class for table "paper_recommendation".
 *
 * The followings are the available columns in table 'paper_recommendation':
 * @property integer $paper_recommendation_id
 * @property integer $subject_id
 * @property integer $exam_paper_id
 * @property integer $sequence
 * @property double $difficuty
 */
class PaperRecommendationModel extends CActiveRecord
{
	public static $DIFFICUTY_MAP = array(
		'0.1'=>0.1,	'0.2'=>0.2,	'0.3'=>0.3,	'0.4'=>0.4,	'0.5'=>0.5,	'0.6'=>0.6,	'0.7'=>0.7,
		'0.8'=>0.8,	'0.9'=>0.9,	'1.0'=>1.0);
	
	private $_examPaperName;
	
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
			array('subject_id, exam_paper_id, sequence', 'numerical', 'integerOnly'=>true),
			array('difficuty', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('paper_recommendation_id, subject_id, exam_paper_id, sequence, difficuty, examPaperName', 'safe', 'on'=>'search'),
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
			'examPaper'=>array(self::BELONGS_TO, 'ExamPaperModel', 'exam_paper_id'),
			'subject'=>array(self::BELONGS_TO, 'SubjectModel', 'subject_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'difficuty' => '难度值',
			'exam_paper_id' => '试卷',
			'examPaperName' => '试卷名称',
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
		$criteria->compare('t.subject_id',$this->subject_id);
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('sequence',$this->sequence);
		$criteria->compare('difficuty',$this->difficuty);
		$criteria->compare('examPaper.name', $this->_examPaperName, true);
		$criteria->with = 'examPaper';

		$sort = new CSort();
		$sort->attributes = array(
			'difficuty' => array('asc'=>'t.difficuty', 'desc'=>'t.difficuty desc'),
			'examPaperName' => array('asc'=>'examPaper.name', 'desc'=>'examPaper.name desc')
		);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
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
	
	public function beforeSave() {
		$metaData = $this->getMetaData ();
		if ($this->getIsNewRecord ()) {
			$sql = 'select max(sequence) as sequence from '.$this->getTableSchema()->rawName.' where subject_id='.$this->subject_id;
			$result = self::model()->findBySql($sql);
			if ($result){
				$this->sequence = $result->sequence + 1;
			}
		}
		return parent::beforeSave ();
	}
	
	public function getExamPaperName(){
		if ($this->_examPaperName === null && $this->examPaper !== null){
			$this->_examPaperName = $this->examPaper->name;
		}
		return $this->_examPaperName;
	}
	
	public function setExamPapername($value){
		$this->_examPaperName = $value;
	}
}
