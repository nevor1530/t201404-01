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
		$questionFilterForm = new QuestionFilterForm;
		
		$questionTypes = array (	
			self::$single_choice_type => '单选题',
			self::$multiple_choice_type => '多选题',
			self::$true_false_type => '判断题',
			self::$material_type => '材料题',
		);
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id = ' . $subject_id;  
		$examPointListData = array();
		$this->genExamPointListData(ExamPointModel::model()->top()->findAll($criteria), $examPointListData, 0);
		
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
		$criteria->order = 'exam_paper_id, material_id, question_id desc';
		$hideAdvancedSearch = true;
		if (isset($_POST['QuestionFilterForm'])) {
			$questionFilterForm->attributes = $_POST['QuestionFilterForm'];
			if ($questionFilterForm->questionType != null) {
				$criteria->addCondition('question_type=' . $questionFilterForm->questionType);
				$hideAdvancedSearch = false;
			}
			
			if ($questionFilterForm->examPaper != null) {
				$criteria->addCondition('exam_paper_id=' . $questionFilterForm->examPaper);
				$hideAdvancedSearch = false;
			}
			
			if ($questionFilterForm->examPoints != null && count($questionFilterForm->examPoints) > 0) {
				$questionIdList = $this->getQuestionIdListByExamPoints($questionFilterForm->examPoints);
				$criteria->addInCondition('question_id', $questionIdList);
				$hideAdvancedSearch = false;
			}
		}
		
		$count = QuestionModel::model()->count($criteria);    
		$pager = new CPagination($count);    
		$pager->pageSize = 4;             
		$pager->applyLimit($criteria);    
		$records = QuestionModel::model()->findAll($criteria);  
		
		$questionList = array();
		$materialIdList = array();
		foreach ($records as $record) {
			$question = $this->convertQuestionModel2Array($record);
			$material_id = $record->material_id;
			if ($material_id != 0) {
				$materialModel = MaterialModel::model()->findByPk($material_id);
				if ($materialModel != null) {
					$question['material_id'] = $material_id;
					$question['material_content'] = $materialModel->content;
					$questionList[] = $question;
				}
			} else {
				$questionList[] = $question;
			}
		}
		
		$this->render('index', array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'questionTypes' => $questionTypes,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'examPointListData' => $examPointListData,
			'questionFilterForm' => $questionFilterForm,
			'questionModel'=>$questionModel,
			'pages'=>$pager,
			'questionList'=>$questionList,
			'hideAdvancedSearch' => $hideAdvancedSearch,
		));
	}	
	
	private function getQuestionIdListByExamPoints($examPoints) {
		$criteria = new CDbCriteria();
		$criteria->addInCondition("exam_point_id", $examPoints);
		$result = QuestionExamPointModel::model()->findAll($criteria);	
		
		$questionIdList = array();
		if ($result != null && count($result) > 0) {
			foreach ($result as $record) {
				$questionIdList[] = $record->question_id;
			}
		}
		return $questionIdList;
	}
	
	public function actionCreateChoiceQuestion($subject_id, $material_id=0) {
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
			'material_id' => $material_id,
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
				$questionModel->material_id = $material_id;
				$questionModel->subject_id = $subject_id;
				$questionModel->question_type = $choiceQuestionForm->questionType;
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
					
					if ($material_id != 0) {
						$this->redirect(array('viewMaterialQuestion', 'subject_id' => $subject_id, 'material_id' => $material_id));
					} else {
						$this->redirect('', $result);
					}
				}
			}
		}
		
		$this->render('create_choice_question', $result);
	}
	
	public function actionCreateTrueOrFalseQuestion($subject_id,$material_id=0) {
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
			'material_id' => $material_id,
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
				$questionModel->subject_id = $subject_id;
				$questionModel->material_id = $material_id;
				$questionModel->question_type = self::$true_false_type;
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
					
					if ($material_id != 0) {
						$this->redirect(array('viewMaterialQuestion', 'subject_id' => $subject_id, 'material_id' => $material_id));
					} else {
						$this->redirect('', $result);
					}
				}
			}
		}
		
		$this->render('create_true_false_question', $result);
	}
	
	public function actionUpdateQuestion($subject_id, $question_id, $material_id=0) {
		$questionModel = QuestionModel::model()->findByPk($question_id);
		if ($questionModel->question_type == self::$true_false_type) {
			$this->actionUpdateTureOrFalseQuestion($subject_id, $material_id, $questionModel);
		}
	}
	
	public function actionUpdateTureOrFalseQuestion($subject_id, $material_id, $questionModel) {
		$question_id = $questionModel->question_id;
		if(isset($_POST['TrueOrFalseQuestionForm'])) {
			$trueOrFalseQuestionForm = new TrueOrFalseQuestionForm;
			$trueOrFalseQuestionForm->attributes=$_POST['TrueOrFalseQuestionForm'];
			if ($trueOrFalseQuestionForm->validate()) {
				$questionModel->exam_paper_id = ($trueOrFalseQuestionForm->examPaper != null) ? $trueOrFalseQuestionForm->examPaper : 0;
				$questionModel->subject_id = $subject_id;
				$questionModel->material_id = $material_id;
				$questionModel->question_type = self::$true_false_type;
				$questionModel->answer = $trueOrFalseQuestionForm->answer;
		
				if ($questionModel->validate() && $questionModel->save()) {
					$questionExtraModel = QuestionExtraModel::model()->findByPk($questionModel->question_id);
					if ($questionExtraModel == null) {
						$questionExtraModel = new QuestionExtraModel;
						$questionExtraModel->question_id = $question_id;
					}
					$questionExtraModel->title = $trueOrFalseQuestionForm->content;
					$questionExtraModel->analysis = $trueOrFalseQuestionForm->analysis;
				
					if ($questionExtraModel->validate()) {
						$questionExtraModel->save();
					}
					
					//save question exam point
					QuestionExamPointModel::model()->deleteAll('question_id='.$question_id);
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
					
					if ($material_id != 0) {
						$this->redirect(array('viewMaterialQuestion', 'subject_id' => $subject_id, 'material_id' => $material_id));
					} else {
						$this->redirect(array('index', 'subject_id' => $subject_id));
					}
				}
			}
		}
		
		$trueOrFalseQuestionForm = new TrueOrFalseQuestionForm;
		$trueOrFalseQuestionForm->examPaper = $questionModel->exam_paper_id;
		$trueOrFalseQuestionForm->content = $questionModel->questionExtra->title;
		$trueOrFalseQuestionForm->answer = $questionModel->answer;
		$trueOrFalseQuestionForm->analysis = $questionModel->questionExtra->analysis;
		
		$questionExamPoints = $questionModel->questionExamPoints;
		$examPointIdList = array();
		foreach ($questionExamPoints as $questionExamPoint) {
			 $examPointIdList[] = $questionExamPoint->exam_point_id;
		}
		$trueOrFalseQuestionForm->examPoints = $examPointIdList;
		
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
			'material_id' => $material_id,
			'subjectModel' => $subjectModel,
			'trueOrFalseQuestionForm' => $trueOrFalseQuestionForm,
			'examPaperListData'=>$this->getExamPaperListData($subject_id),
			'questionAnswerOptions'=>$questionAnswerOptions,
			'examPointListData' => $examPointListData,
		);
		
		$this->render('update_true_false_question', $result);
	}
	
	public function actionCreateMaterialQuestion($subject_id) {
		$materialQuestionForm = new MaterialQuestionForm;
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
	
		$result = array(
			'subject_id' => $subject_id,
			'subjectModel' => $subjectModel,
			'materialQuestionForm' => $materialQuestionForm,
			'examPaperListData' => $this->getExamPaperListData($subject_id),
		);
		
		if(isset($_POST['MaterialQuestionForm'])) {
			$materialQuestionForm->attributes=$_POST['MaterialQuestionForm'];
			if ($materialQuestionForm->validate()) {
				$materialModel = new MaterialModel;
				$materialModel->exam_paper_id = ($materialQuestionForm->examPaper != null) ? $materialQuestionForm->examPaper : 0;
				$materialModel->content = $materialQuestionForm->content;
				$materialModel->subject_id = $subject_id;
				$materialModel->save();
				$material_id = $materialModel->material_id;
				$this->redirect(array('viewMaterialQuestion', 'subject_id' => $subject_id, 'material_id' => $material_id));
			}
		}
					
		$this->render('create_material_question', $result);
	}
	
	public function actionViewMaterialQuestion($subject_id, $material_id) {
		$subjectModel=SubjectModel::model()->findByPk($subject_id);
		if($subjectModel===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$materialModel = MaterialModel::model()->findByPk($material_id);
		if ($materialModel === null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'material_id = ' . $material_id;
		$records = QuestionModel::model()->findAll($criteria);  
		  
		$questionList = array();
		$materialIdList = array();
		foreach ($records as $record) {
			$question = $this->convertQuestionModel2Array($record);
			$questionList[] = $question;
		}  
		
		$result = array(
			'subject_id' => $subject_id,
			'material_id' => $material_id,
			'subjectModel' => $subjectModel,
			'materialContent' => $materialModel->content,
			'questionList' => $questionList,
		);
		
		$this->render('view_material_question', $result);
	}
	
	public function actionDeleteQuestion($subject_id, $question_id = 0, $material_id = 0) {
		if ($question_id != 0) {
			$this->deleteQuestion($question_id);
		} else if ($material_id != 0) {
			MaterialModel::model()->deleteByPk($material_id);
			$questions = QuestionModel::model()->findAll('material_id=:material_id', array(':material_id' => $material_id));
			if ($questions != null && count($questions) > 0) {
				foreach ($questions as $question) {
					$this->deleteQuestion($question->question_id);
				}
			}
		}
		
		if ($material_id != 0 && $question_id != 0) {
			$this->redirect(array('viewMaterialQuestion', 'subject_id' => $subject_id, 'material_id' => $material_id));
		} else {
			$this->redirect(array('index', 'subject_id' => $subject_id));
		}
	}
	
	private function deleteQuestion($question_id) {
		QuestionExtraModel::model()->deleteAll('question_id='.$question_id);
		QuestionExamPointModel::model()->deleteAll('question_id='.$question_id);
		QuestionAnswerOptionModel::model()->deleteAll('question_id='.$question_id);
		QuestionModel::model()->deleteByPk($question_id);
	}
	
	private function convertQuestionModel2Array($questionModel) {
		$question = array();
		$question['id'] = $questionModel->question_id;
		$question['content'] = $questionModel->questionExtra->title;
		$question['analysis'] = $questionModel->questionExtra->analysis;
		if ($questionModel->question_type == self::$single_choice_type || $questionModel->question_type == self::$multiple_choice_type) {
			$answers = explode('|', $questionModel->answer);
			for ($i = 0; $i < count($answers); $i++) {
				$answers[$i] = chr($answers[$i] + 65);
			}

			$question['answer'] = implode(",  ", $answers);
			$questionAnswerOptions = $questionModel->questionAnswerOptions;
			foreach ($questionAnswerOptions as $questionAnswerOption) {
				$question['answerOptions'][] = array(
					'index' => chr($questionAnswerOption->attributes['index'] + 65),
					'description' => $questionAnswerOption->attributes['description'],
				);
			}
		}  else if ($questionModel->question_type == self::$true_false_type) {
			$question['answer'] = ($questionModel->answer == 0) ? '正确' : '错误';
			$question['answerOptions'][] = array(
				'index' => 'A',
				'description' => '正确',
			);
			
			$question['answerOptions'][] = array(
				'index' => 'B',
				'description' => '错误',
			);
		}
		
		$questionExamPoints = $questionModel->questionExamPoints;
		foreach ($questionExamPoints as $questionExamPoint) {
			$examPointId = $questionExamPoint['exam_point_id'];
			$examPointModel = ExamPointModel::model()->findByPk($examPointId);
			$question['questionExamPoints'][] = $examPointModel['name'];
		}
		
		return $question;
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
