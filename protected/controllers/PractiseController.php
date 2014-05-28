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
		
		$countSql = "SELECT count(*) FROM exam_paper_instance WHERE user_id=" .  Yii::app()->user->id;
		$numberOfRecords = Yii::app()->db->CreateCommand($countSql)->queryScalar();
        $pages=new CPagination(intval($numberOfRecords));
        $pages->pageSize = 1;
        
		$sql = "SELECT exam_paper_instance_id,exam_paper_id,exam_point_id as name,start_time,remain_time FROM exam_paper_instance WHERE " .
					"user_id=" .  Yii::app()->user->id . " AND " .
					"exam_paper_id=0" .
				" UNION " .
				"SELECT exam_paper_instance_id,exam_paper.exam_paper_id as exam_paper_id,name,start_time,remain_time FROM exam_paper_instance,exam_paper WHERE " . 
					"user_id=" .  Yii::app()->user->id . " AND " .
					"exam_paper_instance.exam_paper_id=exam_paper.exam_paper_id AND ".
					"subject_id=" . $this->curSubjectId .
				" ORDER BY start_time DESC";
		
		$offset = $pages->currentPage * $pages->pageSize;
		$limit = $pages->pageSize;
		$command = Yii::app()->db->createCommand($sql . " LIMIT $offset,$limit");
		
		$result = $command->queryAll();
		
		$history = array(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			$index = 0;	
			foreach ($result as $record) {
				$history[$index] = array();
				$history[$index]['exam_paper_instance_id'] = $record['exam_paper_instance_id'];
				$history[$index]['start_time'] = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm", $record['start_time']);
				$history[$index]['is_completed'] = ($record['remain_time'] == 0 ? 1 : 0);
				
				if ($record['exam_paper_id'] == 0) {
					$examPointId = $record['name'];
					$examPointModel = ExamPointModel::model()->findByPk($examPointId);
					if ($examPointModel != null) {
						$history[$index]['name'] = '专项训练(' . $examPointModel->name . ')';
					} else {
						$history[$index]['name'] = '专项训练';
					}
				} else {
					$history[$index]['name'] = $record['name'];
				}
				
				if ($history[$index]['is_completed'] == 1) {
					$history[$index]['total_question_count'] = $this->countPaperQuestions($record['exam_paper_instance_id'],  $record['exam_paper_id']);
					$history[$index]['correct_question_count'] = $this->countCorrectQuestions($record['exam_paper_instance_id']);
				}
				
				$index++;
			}
		}
		
		//print_r($history);exit();
		$this->render('history', array(
			'history' => $history, 
			'pages'=>$pages
		));
	}
	
	
	public function actionFavorites($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointRecords = ExamPointModel::model()->top()->findAll($criteria);
		
		$examPoints = array();
		$totalFavoriteQuestionCount = $this->countFavoriteQuestions($examPointRecords, $examPoints);
		
		$result = array(
			'totalFavoriteQuestionCount' => $totalFavoriteQuestionCount,
			'examPoints' => $examPoints,
		);
		
		$this->render('favorites', $result);
	}
	
	public function actionWrongQuestions($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id);
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointRecords = ExamPointModel::model()->top()->findAll($criteria);
		
		$examPoints = array();
		$totalWrongQuestionCount = $this->countWrongQuestions($examPointRecords, $examPoints);
		
		$result = array(
			'totalWrongQuestionCount' => $totalWrongQuestionCount,
			'examPoints' => $examPoints,
		);
		
		$this->render('wrong_questions', $result);
	}
	
	private function countPaperQuestions($examPaperInstanceId, $examPaperId = 0) {
		$criteria = new CDbCriteria();
		if ($examPaperId == 0) {
			$criteria->condition = 'exam_paper_instance_id = ' . $examPaperInstanceId;  
			return QuestionInstanceModel::model()->count($criteria);
		} else {
			$criteria->condition = 'exam_paper_id = ' . $examPaperId;  
			return ExamPaperQuestionModel::model()->count($criteria);
		}
	}
	
	private function countCorrectQuestions($examPaperInstanceId) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question WHERE " .
					"question_instance.exam_paper_instance_id=$examPaperInstanceId AND " .
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer!=question.answer";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
	private function countFavoriteQuestions($examPointRecords, &$result) {
		if ($examPointRecords == null || count($examPointRecords) == 0) {
			return 0;
		}
		
		$totalFavoriteQuestionCount = 0;
		for ($i = 0; $i < count($examPointRecords); $i++) {
			$examPointRecord = $examPointRecords[$i];
			$examPointId = $examPointRecord->exam_point_id;
			$result[$i] = array(
				'id' => $examPointId,
				'name' => $examPointRecord->name,
				'exam_point_ids' => array($examPointId),
			);
			
			if (!empty($examPointRecord->subExamPoints)){
				$subExamPoints = array();
				$this->countFavoriteQuestions($examPointRecord->subExamPoints, $subExamPoints);
				$result[$i]['sub_exam_points'] = $subExamPoints;
				
				$subExamPointIds = array();
				foreach ($subExamPoints as $subExamPoint) {
					$subExamPointIds[] = $subExamPoint['id'];
				}
				$result[$i]['exam_point_ids'] = array_merge($result[$i]['exam_point_ids'], $subExamPointIds);
			} else {
				$result[$i]['sub_exam_points'] = array();
			}
			
			$userId = Yii::app()->user->id;
			$result[$i]['favorite_question_count'] = $this->calFavoriteQuestionCount($userId, $result[$i]['exam_point_ids']);
			$totalFavoriteQuestionCount += $result[$i]['favorite_question_count'];
		}
		
		return $totalFavoriteQuestionCount;
	}
	
	private function calFavoriteQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_favorites.question_id)) as count FROM question_favorites,question,question_exam_point WHERE " .
					"question_favorites.user_id=$userId AND " . 
					"question_favorites.question_id=question_exam_point.question_id AND " .
					"question_favorites.question_id=question.question_id AND " . 
					"question_exam_point.question_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
	}
	
	private function countWrongQuestions($examPointRecords, &$result) {
		if ($examPointRecords == null || count($examPointRecords) == 0) {
			return 0;
		}
		
		$totalWrongQuestionCount = 0;
		for ($i = 0; $i < count($examPointRecords); $i++) {
			$examPointRecord = $examPointRecords[$i];
			$examPointId = $examPointRecord->exam_point_id;
			$result[$i] = array(
				'id' => $examPointId,
				'name' => $examPointRecord->name,
				'exam_point_ids' => array($examPointId),
			);
			
			if (!empty($examPointRecord->subExamPoints)){
				$subExamPoints = array();
				$this->countWrongQuestions($examPointRecord->subExamPoints, $subExamPoints);
				$result[$i]['sub_exam_points'] = $subExamPoints;
				
				$subExamPointIds = array();
				foreach ($subExamPoints as $subExamPoint) {
					$subExamPointIds[] = $subExamPoint['id'];
				}
				$result[$i]['exam_point_ids'] = array_merge($result[$i]['exam_point_ids'], $subExamPointIds);
			} else {
				$result[$i]['sub_exam_points'] = array();
			}
			
			$userId = Yii::app()->user->id;
			$result[$i]['wrong_question_count'] = $this->calWrongQuestionCount($userId, $result[$i]['exam_point_ids']);
			$totalWrongQuestionCount += $result[$i]['wrong_question_count'];
		}
		
		return $totalWrongQuestionCount;
	}
	
	private function calWrongQuestionCount($userId, $examPointIds) {
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question,question_exam_point WHERE " .
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer!=question.answer AND " . 
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
