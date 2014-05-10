<?php

/**
 * This is the model class for table "question_block".
 *
 * The followings are the available columns in table 'question_block':
 * @property integer $question_block_id
 * @property string $name
 * @property string $description
 * @property integer $exam_paper_id
 * @property string $time_length
 * @property integer $question_number
 * @property integer $score
 * @property integer $score_rule
 * @property integer $sequence
 *
 * The followings are the available model relations:
 * @property Question[] $questions
 * @property ExamPaper $exampPaper
 */
class QuestionBlockModel extends CActiveRecord
{
	const SCORE_RULE_NORMAL = 1;
	const SCORE_RULE_MULTI = 2;
	
	public static $SCORE_RULE_MAP = array(
		self::SCORE_RULE_NORMAL => '普通计分（按照对错计分）',
		self::SCORE_RULE_MULTI => '多选题计分',
	);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_block';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, exam_paper_id, question_number, time_length, score, score_rule', 'required'),
			array('exam_paper_id, question_number, score, score_rule, sequence', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>40),
			array('description', 'length', 'max'=>500),
			array('time_length', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_block_id, name, description, exam_paper_id, time_length, question_number, score, score_rule, sequence', 'safe', 'on'=>'search'),
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
			'questions' => array(self::HAS_MANY, 'Question', 'question_block_id'),
			'examPaper' => array(self::BELONGS_TO, 'ExamPaperModel', 'exam_paper_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => '模块名称',
			'description' => '描述',
			'time_length' => '时间(分钟)',
			'question_number' => '题目数',
			'score' => '模块总分',
			'score_rule' => '计分方式',
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

		$criteria->compare('question_block_id',$this->question_block_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('exam_paper_id',$this->exam_paper_id);
		$criteria->compare('time_length',$this->time_length,true);
		$criteria->compare('question_number',$this->question_number);
		$criteria->compare('score',$this->score);
		$criteria->compare('score_rule',$this->score_rule);
		$criteria->compare('sequence',$this->sequence);
		$criteria->order = 'sequence';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuestionBlockModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave() {
		$metaData = $this->getMetaData ();
		if ($this->getIsNewRecord ()) {
			$sql = 'select max(sequence) as sequence from '.$this->getTableSchema()->rawName.' where exam_paper_id='.$this->exam_paper_id;
			$result = self::model()->findBySql($sql);
			if ($result){
				$this->sequence = $result->sequence + 1;
			}
		}
		return parent::beforeSave ();
	}
}
