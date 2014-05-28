<?php

class ExamPointController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/function';
	public $examBankId;
	public $examBankName;
	public $subjects;
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
				'actions'=>array('index', 'newPractise'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		if ($subject_id == 0 && count($this->subjects) == 0) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else if ($subject_id == 0) {
			$subject_id = $this->subjects[0]['id'];
		}
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointRecords = ExamPointModel::model()->top()->findAll($criteria);
		
		$examPoints = array();
		$this->getExamPoints($examPointRecords, $examPoints);
		
		$result = array(
			'examBankId' => $exam_bank_id,
			'subjectId' => $subject_id,
			'examPoints' => $examPoints,
		);
		
		$this->render('index', $result);
	}
	
	public function actionNewPractise($exam_bank_id, $subject_id, $exam_point_id) {
		$this->initial($exam_bank_id, $subject_id);
		$examPointRecord = ExamPointModel::model()->findByPk($exam_point_id);
		
		$subExamPoints = array();
		$this->getExamPoints($examPointRecord->subExamPoints, $subExamPoints);

		$candidateQuestionIds = $this->getQuestionIdsByExamPointId($exam_point_id);
		foreach ($subExamPoints as $subExamPoint) {
			$candidateQuestionIds = array_merge($candidateQuestionIds, $subExamPoint['question_ids']);
		}
		
		$selectedQuestionIds = array_rand($candidateQuestionIds, min(count($candidateQuestionIds), 15));
		$criteria = new CDbCriteria();
		$criteria->addInCondition("question_id", $selectedQuestionIds);
		$questionRecords = QuestionExamPointModel::model()->findAll($criteria);	
		
		$questions = array();
		if ($questionRecords != null) {
			$examPaperInstanceModel = new ExamPaperInstanceModel;
			$examPaperInstanceModel->exam_paper_id = 0;
			$examPaperInstanceModel->exam_point_id = $exam_point_id;
			$examPaperInstanceModel->user_id = $userId = Yii::app()->user->id;
			$examPaperInstanceModel->remain_time = 30;
			
			if ($examPaperInstanceModel->validate() && $examPaperInstanceModel->save()) {
				for ($index = 0; $index < count($questionRecords); $index++) {
					$questionInstanceModel = new QuestionInstanceModel;
					$questionInstanceModel->exam_paper_instance_id = $examPaperInstanceModel->exam_paper_instance_id;
					$questionInstanceModel->question_id = $questionModel->question_id;
					$questionInstanceModel->user_id = $userId = Yii::app()->user->id;
					if ($questionInstanceModel->validate()) {
						$questionInstanceModel->save();
					}
					
					$questionModel = $questionRecords[$index];
					$question[$index]['id'] = $questionModel->question_id;
					$question[$index]['content'] = $questionModel->questionExtra->title;
					if ($questionModel->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $questionModel->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE) {
						$questionAnswerOptions = $questionModel->questionAnswerOptions;
						foreach ($questionAnswerOptions as $questionAnswerOption) {
							$question[$index]['answerOptions'][] = array(
								'index' => chr($questionAnswerOption->attributes['index'] + 65),
								'description' => $questionAnswerOption->attributes['description'],
							);
						}
					}  else if ($questionModel->question_type == QuestionModel::TRUE_FALSE_TYPE) {
						$question[$index]['answerOptions'][] = array(
							array('index' => 'A', 'description' => '正确'),
							array('index' => 'B', 'description' => '错误'),
						);
					}
				}
				
			}
		}
		
		$this->render('new_practise', $questions);
	}
	
	private function initial($exam_bank_id, $subject_id) {
		$this->curTab = Constants::$EXAM_POINT_TAB;
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
	}
	
	private function getExamPoints($examPointRecords, &$result) {
		if ($examPointRecords == null || count($examPointRecords) == 0) {
			return;
		}
		
		for ($i = 0; $i < count($examPointRecords); $i++) {
			$examPointRecord = $examPointRecords[$i];
			$examPointId = $examPointRecord->exam_point_id;
			$result[$i] = array(
				'id' => $examPointId,
				'name' => $examPointRecord->name,
				'exam_point_ids' => array($examPointId),
			);
			
			$curExamPointQuestionIds = $this->getQuestionIdsByExamPointId($examPointId);
			
			if (!empty($examPointRecord->subExamPoints)){
				$subExamPoints = array();
				$this->getExamPoints($examPointRecord->subExamPoints, $subExamPoints);
				$result[$i]['sub_exam_points'] = $subExamPoints;
				$subExamPointIds = array();
				foreach ($subExamPoints as $subExamPoint) {
					$curExamPointQuestionIds = array_merge($curExamPointQuestionIds, $subExamPoint['question_ids']);
					$subExamPointIds[] = $subExamPoint['id'];
				}
				$result[$i]['exam_point_ids'] = array_merge($result[$i]['exam_point_ids'], $subExamPointIds);
			} else {
				$result[$i]['sub_exam_points'] = array();
			}
			
			$curExamPointQuestionIds = array_unique($curExamPointQuestionIds);
			$result[$i]['question_ids'] = $curExamPointQuestionIds;
			$result[$i]['question_count'] = count($curExamPointQuestionIds);
			
			$userId = Yii::app()->user->id;
			$result[$i]['finished_question_count'] = $this->getFinishedQuestionCount($userId, $result[$i]['exam_point_ids']);
			$result[$i]['correct_question_count'] = $this->calCorrectQuestionCount($userId, $result[$i]['exam_point_ids']);
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
	
	private function getFinishedQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question_exam_point WHERE " .
					"question_instance.myanswer IS NOT NULL AND " . 
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_exam_point.question_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
	private function calCorrectQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question,question_exam_point WHERE " .
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer=question.answer AND " . 
					"question_exam_point.question_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
}
