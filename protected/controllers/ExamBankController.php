<?php

class ExamBankController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'info'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->order = '`order`';
		$results = ExamBankModel::model()->findAll($criteria);
		
		$examBanks = array();
		if ($results != null) {
			foreach ($results as $record) {
				$examBankId = $record->exam_bank_id;
				$realExamPaperCount = $this->getRealExamPaperCountByExamBankId($examBankId);
				$questionCount = $this->getQuestionCountByExamBankId($examBankId);
				$examBank = array(
					'id' => $examBankId,
					'name' => $record->name,
					'icon' => Constants::$EXAM_BANK_ICON_DIR_PATH . $record->icon,
					'real_exam_paper_count' => $realExamPaperCount,
					'question_count' => $questionCount,
				);
				$examBanks[] = $examBank;
			}
		}
		
		$this->render('index',array(
			'examBanks' => $examBanks,
		));
	}
	
	public function actionInfo($exam_bank_id, $subject_id = 0) {
		$examBankRecord = ExamBankModel::model()->findByPk($exam_bank_id);
		
		$subjects = array();
		$subjectRecords = $examBankRecord->subjects;
		if ($subjectRecords != null) {
			foreach ($subjectRecords as $subjectRecord) {
				$subjects[] = array(
					'id' => $subjectRecord->subject_id,
					'name' => $subjectRecord->name
				);
			}
		}
		
		if ($subject_id == 0 && count($subjects) == 0) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else if ($subject_id == 0) {
			$subject_id = $subjects[0]['id'];
		}
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointRecords = ExamPointModel::model()->top()->findAll($criteria);
		
		$examPoints = array();
		$this->genExamPointListData($examPointRecords, $examPoints);
		
		header("Content-Type: text/html; charset=utf8");
		print_r($examPoints);exit();
		
		$result = array(
			'exam_bank_name' => $examBankRecord->name,
			'subjects' => $subjects,
			'exam_points' => $examPoints,
		);
		
		$this->render('info', $result);
	}
	
	private function genExamPointListData($examPointRecords, &$result) {
		if ($examPointRecords == null || count($examPointRecords) == 0) {
			return;
		}
		
		for ($i = 0; $i < count($examPointRecords); $i++) {
			$examPointRecord = $examPointRecords[$i];
			$examPointId = $examPointRecord->exam_point_id;
			$result[$i] = array(
				'id' => $examPointId,
				'name' => $examPointRecord->name,
			);
			
			$curExamPointQuestionIds = $this->getQuestionIdsByExamPointId($examPointId);
			
			if (!empty($examPointRecord->subExamPoints)){
				$subExamPoints = array();
				$this->genExamPointListData($examPointRecord->subExamPoints, $subExamPoints);
				$result[$i]['sub_exam_points'] = $subExamPoints;
				foreach ($subExamPoints as $subExamPoint) {
					$curExamPointQuestionIds = array_merge($curExamPointQuestionIds, $this->getQuestionIdsByExamPointId($subExamPoint['id']));
				}
			} else {
				$result[$i]['sub_exam_points'] = array();
			}
			
			$curExamPointQuestionIds = array_unique($curExamPointQuestionIds);
			$result[$i]['question_ids'] = $curExamPointQuestionIds;
			$result[$i]['question_count'] += count($curExamPointQuestionIds);
		}
	}
	
	private function getQuestionIdsByExamPointId($examPointId) {
		$questionIds = array();
		$criteria = new CDbCriteria();
		$criteria->condition = 'exam_point_id = ' . $examPointId;  
		$records = QuestionExamPointModel::model()->findAll($criteria);	
		if ($records != null) {
			foreach ($records as $record) {
				$questionIds[] = $record->question_id;
			}
		}
		return $questionIds;
	}
	
	private function getRealExamPaperCountByExamBankId($examBankId) {
		$sql = "SELECT COUNT(*) as count FROM exam_paper WHERE subject_id IN (" .
					"SELECT subject_id FROM subject WHERE exam_bank_id=$examBankId" .
				") AND is_real = 1";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
	private function getQuestionCountByExamBankId($examBankId) {
		$sql = "SELECT count(DISTINCT(question_id)) as count FROM exam_paper_question WHERE exam_paper_id IN (" . 
					"SELECT exam_paper_id FROM exam_paper WHERE subject_id in (" .
						"SELECT subject_id FROM subject WHERE exam_bank_id=$examBankId" .
					") AND is_real=1" .
				") AND sequence > 0";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
}
