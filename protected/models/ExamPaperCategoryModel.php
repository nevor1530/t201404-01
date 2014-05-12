<?php

/**
 * This is the model class for table "exam_paper_category".
 *
 * The followings are the available columns in table 'exam_paper_category':
 * @property integer $exam_paper_category_id
 * @property integer $category_id
 * @property integer $exam_paper_id
 * @property integer $sequence
 */
class ExamPaperCategoryModel extends CActiveRecord
{
	private $_examPaperName;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_paper_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, exam_paper_id', 'required'),
			array('category_id, exam_paper_id, sequence', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_paper_category_id, category_id, exam_paper_id, sequence', 'safe', 'on'=>'search'),
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
			'category'=>array(self::BELONGS_TO, 'CategoryModel', 'category_id'),
			'examPaper'=>array(self::BELONGS_TO, 'ExamPaperModel', 'exam_paper_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'exam_paper_id' => '试卷名称',
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
		$criteria->with = 'examPaper';
		$criteria->compare('examPaper.name',$this->_examPaperName, true);
		$criteria->order = 'sequence';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPaperCategoryModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getExamPaperName(){
		if ($this->_examPaperName === null && $this->examPaper !== null){
			$this->_examPaperName = $this->examPaper->name;
		}
		return $this->_examPaperName;
	}
	
	public function setExamPaperName($value){
		$this->_examPaperName = $value;
	}
	
	public function beforeSave() {
		$metaData = $this->getMetaData ();
		if ($this->getIsNewRecord ()) {
			$sql = 'select max(sequence) as sequence from '.$this->getTableSchema()->rawName.' where category_id='.$this->category_id;
			$result = self::model()->findBySql($sql);
			if ($result){
				$this->sequence = $result->sequence + 1;
			}
		}
		return parent::beforeSave ();
	}
}
