<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $category_id
 * @property string $name
 * @property integer $subject_id
 */
class CategoryModel extends CActiveRecord
{
	private $_paperCount;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, subject_id', 'required'),
			array('subject_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('category_id, name, subject_id', 'safe', 'on'=>'search'),
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
			'examPaperCategories'=>array(self::HAS_MANY, 'ExamPaperCategoryModel', 'category_id'),
			'subject'=>array(self::BELONGS_TO, 'SubjectModel', 'subject_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => '分类名称',
			'paperCount' => '试卷数量',
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

		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('subject_id',$this->subject_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CategoryModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getPaperCount(){
		if ($this->_paperCount === null && $this->category_id){
			$count = ExamPaperCategoryModel::model()->count('category_id='.$this->category_id);
			$this->_paperCount = $count;
		}
		
		return $this->_paperCount;
	}
	
	protected function beforeDelete()
	{
		$examPaperCategoryModels = $this->examPaperCategories;
		foreach($examPaperCategoryModels as $model){
			$model->delete();
		}
		
		return parent::beforeDelete();
	}
}
