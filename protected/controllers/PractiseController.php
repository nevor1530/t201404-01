<?php
class PractiseController extends FunctionController
{
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
				'actions'=>array('history', 'wrongQuestions', 'newWrongQuestionPractise', 'viewWrongQuestionAnalysis', 'favorites', 'newFavoriteQuestionPractise', 'viewFavoriteQuestionAnalysis'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionHistory($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		
		$countSql = "SELECT count(*) FROM exam_paper_instance WHERE user_id=" .  Yii::app()->user->id;
		$numberOfRecords = Yii::app()->db->CreateCommand($countSql)->queryScalar();
        $pages=new CPagination(intval($numberOfRecords));
        $pages->pageSize = 5;
        
		$sql = "SELECT exam_paper_instance_id,instance_type,exam_paper_id,exam_point_id as name,start_time,is_completed FROM exam_paper_instance WHERE " .
					"user_id=" .  Yii::app()->user->id . " AND " .
					"exam_paper_id=0" .
				" UNION " .
				"SELECT exam_paper_instance_id,instance_type,exam_paper_instance.exam_paper_id as exam_paper_id,name,start_time,is_completed FROM exam_paper_instance,exam_paper WHERE " . 
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
				$history[$index]['is_real_exam_paper'] = ($record['exam_paper_id'] != 0);
				$history[$index]['is_completed'] = $record['is_completed'];
				
				if ($record['instance_type'] == ExamPaperInstanceModel::REAL_EXAM_PAPER_TYPE) {
					$history[$index]['name'] = $record['name'];
				} else {
					$examPointId = $record['name'];
					$examPointModel = ExamPointModel::model()->findByPk($examPointId);
					if ($examPointModel != null) {
						if ($record['instance_type'] == ExamPaperInstanceModel::NORMAL_PRACTISE_TYPE) {
							$history[$index]['name'] = '专项训练(' . $examPointModel->name . ')';
						} else if ($record['instance_type'] == ExamPaperInstanceModel::WRONG_QUESTION_PRACTISE_TYPE) {
							$history[$index]['name'] = '错题训练(' . $examPointModel->name . ')';
						} else if ($record['instance_type'] == ExamPaperInstanceModel::FAVORITE_QUESTION_PRACTISE_TYPE) {
							$history[$index]['name'] = '收藏题训练(' . $examPointModel->name . ')';
						}
					} else {
						$history[$index]['name'] = '专项训练';
					}
				} 
				
				if ($history[$index]['is_completed'] == 1) {
					$history[$index]['total_question_count'] = $this->countPaperQuestions($record['exam_paper_instance_id'],  $record['exam_paper_id']);
					$history[$index]['correct_question_count'] = $this->countCorrectQuestions($record['exam_paper_instance_id']);
				}
				
				$index++;
			}
		}
		
		//header("Content-type: text/html; charset=utf8"); 
		//print_r($history);exit();
		
		$this->render('history', array(
			'history' => $history, 
			'pages'=>$pages
		));
	}
	
	public function actionFavorites($exam_bank_id, $subject_id = 0) {
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		
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
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		
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
	
	public function actionNewWrongQuestionPractise($exam_bank_id, $subject_id, $exam_point_id, $return_url = null) {
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		$examPoint = ExamPointModel::model()->findByPk($exam_point_id);
		$examPointName = $examPoint->name;
		
		$candidateWrongQuestionIds = array();
		$userId = Yii::app()->user->id;
		$this->getWrongQuestionIdsByExamPoint($userId, $examPoint, $candidateWrongQuestionIds);
		$candidateWrongQuestionIds = array_unique($candidateWrongQuestionIds);

		$selectedWrongQuestionIds = array();
		if (count($candidateWrongQuestionIds) > 0) {
			$selectedQuestionIds = $this->randArray($candidateWrongQuestionIds, 15);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("question_id", $selectedQuestionIds);
		$criteria->order = 'material_id, question_id desc';
		$questionModels = QuestionModel::model()->findAll($criteria);	
		
		$questions = array();
		if ($questionModels != null) {
			$examPaperInstanceModel = new ExamPaperInstanceModel;
			$examPaperInstanceModel->instance_type = ExamPaperInstanceModel::WRONG_QUESTION_PRACTISE_TYPE;
			$examPaperInstanceModel->exam_paper_id = 0;
			$examPaperInstanceModel->exam_point_id = $exam_point_id;
			$examPaperInstanceModel->user_id = Yii::app()->user->id;
			$examPaperInstanceModel->start_time = date("Y-m-d H:i:s");
			$examPaperInstanceModel->elapsed_time = 0;
			$examPaperInstanceModel->is_completed = 0;
			
			if ($examPaperInstanceModel->validate() && $examPaperInstanceModel->save()) {
				$userId = Yii::app()->user->id;
				for ($index = 0; $index < count($questionModels); $index++) {
					$questionModel = $questionModels[$index];
					
					$questionInstanceModel = new QuestionInstanceModel;
					$questionInstanceModel->exam_paper_instance_id = $examPaperInstanceModel->exam_paper_instance_id;
					$questionInstanceModel->question_id = $questionModel->question_id;
					$questionInstanceModel->user_id = Yii::app()->user->id;
					if ($questionInstanceModel->validate()) {
						$questionInstanceModel->save();
					}
					
					$question = $this->getQuestionDetailFromModel($questionModel, null, false);
					$question['questionInstanceId'] = $questionInstanceModel->question_instance_id;
					$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionModel->question_id);
					$questions[$index] = $question;
				}
			}
				
			$this->render('//examPoint/practise', array(
				'returnUrl' => $return_url,
				'practiseName' => '错题训练：【' . $examPointName . '】',
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'elapsedTime' => $examPaperInstanceModel->elapsed_time,
				'questions' => $questions,
			));
		} else {
			if ($return_url != null) {
				$this->redirect(urldecode($return_url));
			} else {
				$this->redirect(array('history', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
			}	
		}
	}
	
	public function actionViewWrongQuestionAnalysis($exam_bank_id, $subject_id, $exam_point_id) {
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		$examPoint = ExamPointModel::model()->findByPk($exam_point_id);
		$examPointName = $examPoint->name;
		
		$wrongQuestionIds = array();
		$userId = Yii::app()->user->id;
		$this->getWrongQuestionIdsByExamPoint($userId, $examPoint, $wrongQuestionIds);
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("question_id", $wrongQuestionIds);
		$criteria->order = 'material_id, question_id desc';
		$questionModels = QuestionModel::model()->findAll($criteria);	
		
		$questions = array();
		if ($questionModels != null) {
			foreach ($questionModels as $questionModel) {
				$question = $this->getQuestionDetailFromModel($questionModel, null, true);
				$question['questionInstanceId'] = $questionInstanceModel->question_instance_id;
				$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionModel->question_id);
				$questions[] = $question;
			}
		}
		
		$this->render('analysis', array(
			'pageName' => '错题解析',
			'analysisName' => '错题查看：【' . $examPointName . '】',
			'questions' => $questions,
		));
	}
	
	public function actionNewFavoriteQuestionPractise($exam_bank_id, $subject_id, $exam_point_id, $return_url = null) {
		$this->initial($exam_bank_id, $subject_id, Constants::$PRACTISE_TAB);
		$examPoint = ExamPointModel::model()->findByPk($exam_point_id);
		$examPointName = $examPoint->name;
		
		$candidateFavoriteQuestionIds = array();
		$userId = Yii::app()->user->id;
		$this->getFavoriteQuestionIdsByExamPoint($userId, $examPoint, $candidateFavoriteQuestionIds);
		$candidateFavoriteQuestionIds = array_unique($candidateFavoriteQuestionIds);

		$selectedFavoriteQuestionIds = array();
		if (count($candidateFavoriteQuestionIds) > 0) {
			$selectedFavoriteQuestionIds = $this->randArray($candidateFavoriteQuestionIds, 15);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("question_id", $selectedFavoriteQuestionIds);
		$criteria->order = 'material_id, question_id desc';
		$questionModels = QuestionModel::model()->findAll($criteria);	
		
		$questions = array();
		if ($questionModels != null) {
			$examPaperInstanceModel = new ExamPaperInstanceModel;
			$examPaperInstanceModel->instance_type = ExamPaperInstanceModel::FAVORITE_QUESTION_PRACTISE_TYPE;
			$examPaperInstanceModel->exam_paper_id = 0;
			$examPaperInstanceModel->exam_point_id = $exam_point_id;
			$examPaperInstanceModel->user_id = Yii::app()->user->id;
			$examPaperInstanceModel->start_time = date("Y-m-d H:i:s");
			$examPaperInstanceModel->elapsed_time = 0;
			$examPaperInstanceModel->is_completed = 0;
			
			if ($examPaperInstanceModel->validate() && $examPaperInstanceModel->save()) {
				$userId = Yii::app()->user->id;
				for ($index = 0; $index < count($questionModels); $index++) {
					$questionModel = $questionModels[$index];
					
					$questionInstanceModel = new QuestionInstanceModel;
					$questionInstanceModel->exam_paper_instance_id = $examPaperInstanceModel->exam_paper_instance_id;
					$questionInstanceModel->question_id = $questionModel->question_id;
					$questionInstanceModel->user_id = Yii::app()->user->id;
					if ($questionInstanceModel->validate()) {
						$questionInstanceModel->save();
					}
					
					$question = $this->getQuestionDetailFromModel($questionModel, null, false);
					$question['questionInstanceId'] = $questionInstanceModel->question_instance_id;
					$question['is_favorite'] = $this->isFavoriteQuestion($userId, $questionModel->question_id);
					$questions[$index] = $question;
				}
			}
				
			$this->render('//examPoint/practise', array(
				'returnUrl' => $return_url,
				'practiseName' => '收藏题训练：【' . $examPointName . '】',
				'examPaperInstanceId' => $examPaperInstanceModel->exam_paper_instance_id,
				'elapsedTime' => $examPaperInstanceModel->elapsed_time,
				'questions' => $questions,
			));
		} else {
			if ($return_url != null) {
				$this->redirect(urldecode($return_url));
			} else {
				$this->redirect(array('history', 'exam_bank_id' => $exam_bank_id, 'subject_id' => $subject_id));
			}	
		}
	}
	
	public function actionViewFavoriteQuestionAnalysis($exam_bank_id, $subject_id, $exam_point_id) {
		
	}
	
	private function getWrongQuestionIdsByExamPoint($userId, $examPoint, &$result) {
		$questionIds = $this->getWrongQuestionIdsByExamPointId($userId, $examPoint->exam_point_id);
		$result = array_merge($result, $questionIds);
		
		$subExamPoints = $examPoint->subExamPoints;
		if (!empty($subExamPoints)) {
			foreach ($subExamPoints as $subExamPoint) {
				$this->getWrongQuestionIdsByExamPoint($userId, $subExamPoint, $result);
			}
		}
	}
	
	private function getWrongQuestionIdsByExamPointId($userId, $examPointId) {
		$sql = "SELECT DISTINCT(wrong_question.question_id) as question_id FROM wrong_question,question,question_exam_point WHERE " .
					"wrong_question.user_id=$userId AND " . 
					"wrong_question.question_id=question_exam_point.question_id AND " .
					"wrong_question.question_id=question.question_id AND " . 
					"question_exam_point.exam_point_id=$examPointId";
					
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		
		$questionIds = array();
		if ($result != null && is_array($result) && count($result) > 0) {
			foreach ($result as $record) {
				$questionIds[] = $record['question_id'];
			}
		}
		
		return $questionIds;
	}
	
	private function getFavoriteQuestionIdsByExamPoint($userId, $examPoint, &$result) {
		$questionIds = $this->getFavoriteQuestionIdsByExamPointId($userId, $examPoint->exam_point_id);
		$result = array_merge($result, $questionIds);
		
		$subExamPoints = $examPoint->subExamPoints;
		if (!empty($subExamPoints)) {
			foreach ($subExamPoints as $subExamPoint) {
				$this->getFavoriteQuestionIdsByExamPoint($userId, $subExamPoint, $result);
			}
		}
	}
	
	private function getFavoriteQuestionIdsByExamPointId($userId, $examPointId) {
		$sql = "SELECT DISTINCT(question_favorites.question_id) as question_id FROM question_favorites,question,question_exam_point WHERE " .
			"question_favorites.user_id=$userId AND " . 
			"question_favorites.question_id=question_exam_point.question_id AND " .
			"question_favorites.question_id=question.question_id AND " . 
			"question_exam_point.exam_point_id=$examPointId";
					
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		
		$questionIds = array();
		if ($result != null && is_array($result) && count($result) > 0) {
			foreach ($result as $record) {
				$questionIds[] = $record['question_id'];
			}
		}
		
		return $questionIds;
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
					"question_instance.myanswer=question.answer";
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
					"question_exam_point.exam_point_id IN(" . implode(',' , $examPointIds) . ")";
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
		$sql = "SELECT count(DISTINCT(wrong_question.question_id)) as count FROM wrong_question,question,question_exam_point WHERE " .
					"wrong_question.user_id=$userId AND " . 
					"wrong_question.question_id=question_exam_point.question_id AND " .
					"wrong_question.question_id=question.question_id AND " . 
					"question_exam_point.exam_point_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
		
		$sql = "SELECT count(DISTINCT(question_instance.question_id)) as count FROM question_instance,question,question_exam_point WHERE " .
					"question_instance.user_id=$userId AND " . 
					"question_instance.question_id=question_exam_point.question_id AND " .
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer IS NOT NULL AND " . 
					"question_instance.myanswer!=question.answer AND " . 
					"question_exam_point.exam_point_id IN(" . implode(',' , $examPointIds) . ")";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		if ($result != null && is_array($result) && count($result) > 0) {
			return $result[0]['count'];
		}
		
		return 0;
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
	
}
