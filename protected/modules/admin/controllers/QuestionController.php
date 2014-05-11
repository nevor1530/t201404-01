<?php

class QuestionController extends AdminController
{
	public static $single_choice_type = 0;
	public static $multiple_choice_type = 1;
	public static $true_false_type = 2;
	public static $material_type = 3;
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete, create, ajaxCreate, ajaxUpdate, visible', // we only allow deletion via POST request
		);
	}
	
	public function actionIndex($subject_id) {
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$questionModel=new QuestionModel('search');
		$questionModel->unsetAttributes();  // clear any default values
		if(isset($_GET['QuestionModel'])) {
			$questionModel->attributes=$_GET['QuestionModel'];
		}

		$criteria = new CDbCriteria();    
		$count = QuestionModel::model()->count($criteria);    
		$pager = new CPagination($count);    
		$pager->pageSize = 3;             
		$pager->applyLimit($criteria);    
		$records = QuestionModel::model()->findAll($criteria);  
		  
		$questionList = array();
		foreach ($records as $record) {
			$question = array();
			$question['id'] = $record->question_id;
			$question['content'] = $record->questionExtra->title;
			$question['analysis'] = $record->questionExtra->analysis;
			if ($record->question_type == self::$single_choice_type || $record->question_type == self::$multiple_choice_type) {
				$answers = explode('|', $record->answer);
				for ($i = 0; $i < count($answers); $i++) {
					$answers[$i] = chr($answers[$i] + 65);
				}

				$question['answer'] = implode(",  ", $answers);
				$questionAnswerOptions = $record->questionAnswerOptions;
				foreach ($questionAnswerOptions as $questionAnswerOption) {
					$question['answerOptions'][] = array(
						'index' => chr($questionAnswerOption->attributes['index'] + 65),
						'description' => $questionAnswerOption->attributes['description'],
					);
				}
			}  else if ($record->question_type == self::$true_false_type) {
				$question['answer'] = ($record->answer == 0) ? '正确' : '错误';
				$question['answerOptions'][] = array(
					'index' => 'A',
					'description' => '正确',
				);
				
				$question['answerOptions'][] = array(
					'index' => 'B',
					'description' => '错误',
				);
			}
			
			$questionExamPoints = $record->questionExamPoints;
			foreach ($questionExamPoints as $questionExamPoint) {
				$examPointId = $questionExamPoint['exam_point_id'];
				$examPointModel = ExamPointModel::model()->findByPk($examPointId);
				$question['questionExamPoints'][] = $examPointModel['name'];
			}
			
			$questionList[] = $question;
		}
		
		$this->render('index', array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'questionModel'=>$questionModel,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'pages'=>$pager,
			'questionList'=>$questionList,
		));
	}	
	
	public function actionCreateChoiceQuestion($subject_id) {
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		$choiceQuestionForm=new ChoiceQuestionForm;
		$choiceQuestionTypes = array (	
			self::$single_choice_type => '单选',
			self::$multiple_choice_type => '多选',
		);
		$choiceQuestionForm->questionType = self::$single_choice_type;
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointListData = array();
		$this->genExamPointListData(ExamPointModel::model()->top()->findAll($criteria), $examPointListData, 0);
		
		$result = array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'choiceQuestionForm' => $choiceQuestionForm,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'choiceQuestionTypes' => $choiceQuestionTypes,
			'examPointListData' => $examPointListData,
		);
		
		if(isset($_POST['ChoiceQuestionForm'])) {
			$choiceQuestionForm->attributes = $_POST['ChoiceQuestionForm'];
			if ($choiceQuestionForm->validate()) {
				// save question
				$questionModel = new QuestionModel;
				$questionModel->exam_paper_id = ($choiceQuestionForm->examPaper != null) ? $choiceQuestionForm->examPaper : 0;
				$questionModel->question_block_id = 0;
				$questionModel->question_type = $choiceQuestionForm->questionType;
				$questionModel->index = ($choiceQuestionForm->questionNumber != null) ? $choiceQuestionForm->questionNumber : 0;
				if ($choiceQuestionForm->questionType == self::$single_choice_type) {
					$questionModel->answer = $choiceQuestionForm->answer;
				} else if ($choiceQuestionForm->questionType == self::$multiple_choice_type) {
					$questionModel->answer = implode("|", $choiceQuestionForm->answer);
				}
				
				if ($questionModel->validate() && $questionModel->save()) {
					// save question title
					$questionExtraModel = new QuestionExtraModel;
					$questionExtraModel->question_id = $questionModel->question_id;
					$questionExtraModel->title = $choiceQuestionForm->content;
					$questionExtraModel->analysis = $choiceQuestionForm->analysis;
					
					if ($questionExtraModel->validate() && $questionExtraModel->save()) {
						// save answer options
						$keys = array_keys($_POST['ChoiceQuestionForm']);
						foreach ($keys as $key) {
							if (strpos($key, 'answerOption') === 0) {
								$answerOptionIndex = str_replace('answerOption', '', $key);
								$answerOptionDescription = $_POST['ChoiceQuestionForm'][$key];
					
								$questionAnswerOptionModel = new QuestionAnswerOptionModel;
								$questionAnswerOptionModel->question_id = $questionModel->question_id;
								$questionAnswerOptionModel->index = $answerOptionIndex;
								$questionAnswerOptionModel->description = $answerOptionDescription;
								if ($questionAnswerOptionModel->validate()) {
									$questionAnswerOptionModel->save();
								}
							}
						}
					}
					
					//save question exam point
					if ($choiceQuestionForm->examPoints != null) {
						foreach ($choiceQuestionForm->examPoints as $key => $examPointId) {
							$questionExamPointModel = new QuestionExamPointModel;
							$questionExamPointModel->question_id = $questionModel->question_id;
							$questionExamPointModel->exam_point_id = $examPointId;
							if ($questionExamPointModel->validate()) {
								$questionExamPointModel->save();
							}
						}
					}
					
					$this->redirect('', $result);
				}
			}
		}
		
		$this->render('create_choice_question', $result);
	}
	
	public function actionCreateTrueOrFalseQuestion($subject_id) {
		$trueOrFalseQuestionForm = new TrueOrFalseQuestionForm;
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		$questionAnswerOptions = array('0' => '√', '1' => 'X');
	
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointListData = array();
		$this->genExamPointListData(ExamPointModel::model()->top()->findAll($criteria), $examPointListData, 0);
		
		$result = array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'trueOrFalseQuestionForm' => $trueOrFalseQuestionForm,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'questionAnswerOptions'=>$questionAnswerOptions,
			'examPointListData' => $examPointListData,
		);
			
		if(isset($_POST['TrueOrFalseQuestionForm'])) {
			$trueOrFalseQuestionForm->attributes=$_POST['TrueOrFalseQuestionForm'];
			if ($trueOrFalseQuestionForm->validate()) {
				$questionModel = new QuestionModel;
				$questionModel->exam_paper_id = ($trueOrFalseQuestionForm->examPaper != null) ? $trueOrFalseQuestionForm->examPaper : 0;
				$questionModel->question_block_id = 0;
				$questionModel->question_type = self::$true_false_type;
				$questionModel->material_id = $MaterialModel->material_id;
				$questionModel->index = ($trueOrFalseQuestionForm->questionNumber != null) ? $trueOrFalseQuestionForm->questionNumber : 0;
				$questionModel->answer = $trueOrFalseQuestionForm->answer;
		
				if ($questionModel->validate() && $questionModel->save()) {
					$questionExtraModel = new QuestionExtraModel;
					$questionExtraModel->question_id = $questionModel->question_id;
					$questionExtraModel->title = $trueOrFalseQuestionForm->content;
					$questionExtraModel->analysis = $trueOrFalseQuestionForm->analysis;
				
					if ($questionExtraModel->validate()) {
						$questionExtraModel->save();
					}
					
					//save question exam point
					if ($trueOrFalseQuestionForm->examPoints != null) {
						foreach ($trueOrFalseQuestionForm->examPoints as $key => $examPointId) {
							$questionExamPointModel = new QuestionExamPointModel;
							$questionExamPointModel->question_id = $questionModel->question_id;
							$questionExamPointModel->exam_point_id = $examPointId;
							if ($questionExamPointModel->validate()) {
								$questionExamPointModel->save();
							}
						}
					}
					
					$this->redirect('', $result);
				}
			}
		}
		
		$this->render('create_true_false_question', $result);
	}
	
	public function actionCreateMaterialQuestion($subject_id) {
		
	}
	
	public function actionDeleteQuestion($subject_id, $question_id) {
		QuestionExtraModel::model()->deleteAll('question_id='.$question_id);
		QuestionExamPointModel::model()->deleteAll('question_id='.$question_id);
		QuestionAnswerOptionModel::model()->deleteAll('question_id='.$question_id);
		QuestionModel::model()->deleteByPk($question_id);
		$this->redirect(array('index', 'subject_id' => $subject_id));
	}
	
	private function getExamPaperListData($subject_id) {
		$examPaperModel=ExamPaperModel::model()->findAll('subject_id=:subject_id', array(':subject_id' => $subject_id));
		$examPaperListData = CHtml::listData($examPaperModel, 'exam_paper_id', 'name');
		return $examPaperListData;
	}
	
	private function genExamPointListData($models, &$result, $level) {
		$prefix = '';
		for ($i = 0; $i < $level; $i++) {
			$prefix .= '----';
		}
		
		foreach($models as $model) {
			$result[$model->exam_point_id] = $prefix . $model->name;
			if (!empty($model->subExamPoints)){
				 $this->genExamPointListData($model->subExamPoints, $result, $level + 1);
			}
		}
	}
	
}
