<?php

/**
 * This is the model class for table "exam_point".
 *
 * The followings are the available columns in table 'exam_point':
 * @property integer $exam_point_id
 * @property string $name
 * @property integer $pid
 * @property integer $subject_id
 * @property integer $order
 * @property integer $visible
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Subject $subject
 * @property QuestionExamPoint[] $questionExamPoints
 */
class ExamPointModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_point';
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
			array('pid, subject_id, order, visible', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('description', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_point_id, name, pid, subject_id, order, visible, description', 'safe', 'on'=>'search'),
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
			'questionExamPoints' => array(self::HAS_MANY, 'QuestionExamPoint', 'exam_point_id'),
			'subExamPoints' => array(self::HAS_MANY, 'ExamPointModel', 'pid', 'order'=>'`order` asc'),
		);
	}
	
	public function scopes()
	{
		return array(
			'top'=>array(
				'condition'=>'pid=0',
				'order'=>'`order` asc',
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => '考点名称',
			'visible' => '前台是否显示',
			'description' => '描述',
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

		$criteria->compare('exam_point_id',$this->exam_point_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('order',$this->order);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamPointModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave() {
		$metaData = $this->getMetaData ();
		if ($this->getIsNewRecord ()) {
			$sql = 'select max(`order`) as `order` from '.$this->getTableSchema()->rawName.' where pid='.$this->pid;
			$result = self::model()->findBySql($sql);
			if ($result){
				$this->order = $result->order + 1;
			}
		}
		return parent::beforeSave ();
	}
}
