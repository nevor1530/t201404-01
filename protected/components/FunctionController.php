<?php
class FunctionController extends Controller 
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/function';
	public $examBankId;
	public $examBankName;
	public $subjects;
	public $curSubjectId;
	public $curTab;
	
	protected function initial($exam_bank_id, $subject_id, $curTab) {
		$this->examBankId = $exam_bank_id;
		$this->curTab = $curTab;
		$this->curSubjectId = $subject_id;
		
		$examBankRecord = ExamBankModel::model()->findByPk($exam_bank_id);
		$this->examBankName = $examBankRecord->name;
		
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
	
	public function actionAjaxAddQustionToFavorites($question_id) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('user_id = ' . Yii::app()->user->id);  
		$criteria->addCondition('question_id = ' . $question_id);  
		$results = QuestionFavoritesModel::model()->findAll($criteria);
		if ($results != null && count($results) > 0) {
			foreach ($results as $record) {
				$record->delete();
			}
			echo json_encode(array('status'=>0, 'action'=>'cancel'));
			Yii::app()->end();
		}
		
		$questionFavoritesModel = new QuestionFavoritesModel;
		$questionFavoritesModel->user_id = Yii::app()->user->id;
		$questionFavoritesModel->question_id = $question_id;
		if ($questionFavoritesModel->validate()) {
			$questionFavoritesModel->save();
			echo json_encode(array('status'=>0, 'action' => 'add'));
		} else {
			echo json_encode(array('status'=>1, 'errMsg'=>CHtml::errorSummary($questionFavoritesModel)));
		}
		Yii::app()->end();
	}
	
	protected function getQuestionDetailFromModel($questionModel, $myAnswer = array(), $withAnalysis = false) {
		$question = array();
		$question['questionId'] = $questionModel->question_id;
		$question['content'] = $questionModel->questionExtra->title;
		$question['answerOptions'] = array();
		$question['questionType'] = $questionModel->question_type;
		$question['isAnswered'] = ($myAnswer != null && count($myAnswer) > 0);
		
		if ($questionModel->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $questionModel->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE) {
			$questionAnswerOptions = $questionModel->questionAnswerOptions;
			foreach ($questionAnswerOptions as $questionAnswerOption) {
				$question['answerOptions'][] = array(
					'index' =>$questionAnswerOption->attributes['index'],
					'description' => $questionAnswerOption->attributes['description'],
					'isSelected' =>  $myAnswer != null && in_array($questionAnswerOption->index, $myAnswer),
				);
			}
		}  else if ($questionModel->question_type == QuestionModel::TRUE_FALSE_TYPE) {
			$question['answerOptions'][0] = array('index' => '0', 'description' => '正确', 'isSelected' => $myAnswer != null && in_array(0, $myAnswer));
			$question['answerOptions'][1] = array('index' => '1', 'description' => '错误', 'isSelected' => $myAnswer != null && in_array(1, $myAnswer));
		}
		
		$material_id = $questionModel->material_id;
		if ($material_id != 0) {
			$materialModel = MaterialModel::model()->findByPk($material_id);
			if ($materialModel != null) {
				$question['material_id'] = $material_id;
				$question['material_content'] = $materialModel->content;
			}
		}
		
		if ($withAnalysis) {
			$question['analysis'] = $questionModel->questionExtra->analysis;
			$questionExamPoints = $questionModel->questionExamPoints;
			foreach ($questionExamPoints as $questionExamPoint) {
				$examPointId = $questionExamPoint['exam_point_id'];
				$examPointModel = ExamPointModel::model()->findByPk($examPointId);
				$question['questionExamPoints'][] = $examPointModel['name'];
			}
		}
		
		return $question;
	}
	
	protected function isFavoriteQuestion($userId, $questionId) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('user_id = ' . $userId);  
		$criteria->addCondition('question_id = ' . $questionId); 
		return (QuestionFavoritesModel::model()->count($criteria) > 0);
	}
	
	protected function completePractise($userId, $examPaperInstanceId) {
		$examPaperInstanceModel = ExamPaperInstanceModel::model()->findByPk($examPaperInstanceId);
		if ($examPaperInstanceModel != null  && $examPaperInstanceModel->user_id == $userId) {
			$examPaperInstanceModel->is_completed = 1;
			$examPaperInstanceModel->save();
			$this->recordWrongQuestions($userId, $examPaperInstanceId);
		}
	}
	
	protected function recordWrongQuestions($userId, $examPaperInstanceId) {
		$sql = "SELECT question_instance.question_id as question_id,myanswer FROM question_instance,question WHERE " .
					"question_instance.user_id=$userId AND " . 
					"question_instance.exam_paper_instance_id=$examPaperInstanceId AND " . 
					"question_instance.question_id=question.question_id AND " . 
					"question_instance.myanswer IS NOT NULL AND " . 
					"question_instance.myanswer!=question.answer";
					
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryAll(); 
		
		if ($result != null && is_array($result) && count($result) > 0) {
			foreach ($result as $record) {
				$questionId = $record['question_id'];
				$myanswer = $record['myanswer'];
				
				$criteria = new CDbCriteria();
				$criteria->addCondition('question_id = ' . $questionId);
				$criteria->addCondition('user_id = ' . $userId);
				$wrongQuestionModels = WrongQuestionModel::model()->findAll($criteria);
				if ($wrongQuestionModels == null) {
					$wrongQuestionModel = new WrongQuestionModel;
					$wrongQuestionModel->user_id = $userId;
					$wrongQuestionModel->question_id = $questionId;
					$wrongQuestionModel->myanswer = $myanswer;
					$wrongQuestionModel->save();
				} else {
					if (count($wrongQuestionModels) > 0) {
						$wrongQuestionModel = $wrongQuestionModels[0];
						$wrongQuestionModel->myanswer = $myanswer;
						$wrongQuestionModel->save();
					}
				}
			}
		}
	}
	
	protected function randArray($array, $number) {
		$result = array();
		if (count($array) > 0 && $number >= 1) {
			$selectedKeys = array_rand($array, min(count($array), $number));
			if (is_array($selectedKeys)) {
				foreach ($selectedKeys as $key) {
					$result[] = $array[$key];
				}
			} else {
				$result[] = $array[$selectedKeys];
			}
			
		}
		return $result;
	}
	
}
?>