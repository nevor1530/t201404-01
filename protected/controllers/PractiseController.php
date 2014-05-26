<?php
class PractiseController extends Controller
{
	public $layout='//layouts/function';
	public $examBankId;
	public $examBankName;
	public $subjects;
	public $curSubjectId;
	public $curTab;

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('favorites', 'history', 'wrongQuestions'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	private function initial($exam_bank_id, $subject_id) {
		$this->curTab = Constants::$PRACTISE_TAB;
		$examBankRecord = ExamBankModel::model()->findByPk($exam_bank_id);
		$this->examBankName = $examBankRecord->name;
		$this->examBankId = $exam_bank_id;
		
		$subjects = array();
		$subjectRecords = $examBankRecord->subjects;
		if ($subjectRecords != null) {
			for ($i = 0; $i < count($subjectRecords); $i++) {
				$subjectRecord = $subjectRecords[$i];
				$subjects[] = array(
					'id' => $subjectRecord->subject_id,
					'name' => $subjectRecord->name,
					'is_current' => (($subject_id == 0 && $i == 0) || $subject_id == $subjectRecord->subject_id),
				);
			}
		}
		$this->subjects = $subjects;
		
		if ($subject_id == 0 && count($subjects) == 0) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else if ($subject_id == 0) {
			$this->curSubjectId = $subjects[0]['id'];
		} else {
			$this->curSubjectId = $subject_id;
		}
	}
	
	public function actionHistory($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		
		$sql = "SELECT exam_paper_instance_id,exam_paper_id,exam_point_id as name,start_time,remain_time FROM exam_paper_instance WHERE " .
					"user_id=" .  Yii::app()->user->id . " AND " .
					"exam_paper_id=0" .
				" UNION " .
				"SELECT exam_paper_instance_id,exam_paper.exam_paper_id as exam_paper_id,name,start_time,remain_time FROM exam_paper_instance,exam_paper WHERE " . 
					"user_id=" .  Yii::app()->user->id . " AND " .
					"exam_paper_instance.exam_paper_id=exam_paper.exam_paper_id AND ".
					"subject_id=" . $this->curSubjectId .
				" ORDER BY start_time DESC";
		
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
		
		$history = array(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			$index = 0;	
			foreach ($result as $record) {
				$history[$index] = array (
					'exam_paper_instance_id' => $record['exam_paper_instance_id'],
					'start_time' =>$record['start_time'],
				);
				
				$index++;
			}
		}
		
		$this->render('history', $history);
	}
	
	public function actionFavorites($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		$this->render('favorites', array());
	}
	
	public function actionWrongQuestions($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		$this->render('wrong_questions', array());
	}
}
