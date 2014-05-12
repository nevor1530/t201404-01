<?php

class ExamPaperQuestionController extends AdminController
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ExamPaperQuestionModel;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ExamPaperQuestionModel']))
		{
			$model->attributes=$_POST['ExamPaperQuestionModel'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->exam_paper_question_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ExamPaperQuestionModel']))
		{
			$model->attributes=$_POST['ExamPaperQuestionModel'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->exam_paper_question_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($exam_paper_id)
	{
		$res = array();
		$questionStatus = array();	// 每个题目的状态，key为block id,value为array(sequence=>value)
		$paperQuestionNumber = 0;	// 总共的题目数量
		$paperQuestionAssignNumber = 0;	// 已分配的题目数量
		
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if (!$examPaperModel)
			throw new CHttpException(404,'The requested page does not exist.');
		
		// 已分配的题目数量
		$paperQuestionAssignNumber = ExamPaperQuestionModel::model()->count('sequence>0 and exam_paper_id='.$exam_paper_id);
		
		// 遍历blocks，取得每个题目的状态
		$questionBlockModels = $examPaperModel->questionBlocks;
		foreach($questionBlockModels as $questionBlockModel){
			// 累加题目数量
			$paperQuestionNumber += $questionBlockModel->question_number;
			
			$status = array();
			$examPaperQuestionModels = ExamPaperQuestionModel::model()->findAll('sequence>0 and question_block_id='.$questionBlockModel->question_block_id);
			foreach($examPaperQuestionModels as $examPaperQuestionModel){
				$status[$examPaperQuestionModel->sequence] = true;
			}
			$questionStatus[$questionBlockModel->primaryKey] = $status;
		}
		
		$criteria = new CDbCriteria();
		$criteria->with = 'question';
		$criteria->addCondition('sequence=0');
		$criteria->addCondition('t.exam_paper_id='.$exam_paper_id);
		$unAssignedQuestionDataProvider = new CActiveDataProvider('ExamPaperQuestionModel', array('criteria'=>$criteria));
//		$unAssignedQuestionModels = ExamPaperQuestionModel::model()->findAll($criteria);
		
		$res['examPaperModel'] = $examPaperModel;
		$res['paperQuestionNumber'] = $paperQuestionNumber;
		$res['paperQuestionAssignNumber'] = $paperQuestionAssignNumber;
		$res['unAssignedQuestionDataProvider'] = $unAssignedQuestionDataProvider;
		$res['subjectModel'] = $examPaperModel->subject;
		$res['questionStatus'] = $questionStatus;
		$res['questionBlockModels'] = $questionBlockModels;
		$this->render('index', $res);
	}
	
	public function actionChoose($exam_paper_id, $exam_block_id=null, $sequence=null){
		// TODO 只考了代码，逻辑未调整
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if (!$examPaperModel)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$subjectModel = $examPaperModel->subject;
		
		$questionFilterForm = new QuestionFilterForm;
		
		$questionTypes = array (	
			self::$single_choice_type => '单选题',
			self::$multiple_choice_type => '多选题',
			self::$true_false_type => '判断题',
			self::$material_type => '材料题',
		);
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'subject_id='.$subjectModel->subject_id;  
		$examPointListData = array();
		$this->genExamPointListData(ExamPointModel::model()->top()->findAll($criteria), $examPointListData, 0);
		
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

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ExamPaperQuestionModel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exam-paper-question-model-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
