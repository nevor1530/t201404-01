<?php

class ExamPaperQuestionController extends AdminController
{
		public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + operate, sequence', // we only allow deletion via POST request
		);
	}
	
	/**
	 * @param ExamPaperQuestionModel[exam_paper_id]
	 * @param ExamPaperQuestionModel[question_id]
	 * @param ExamPaperQuestionModel[material_id]
	 */
	public function actionOperate(){
		$action = $_POST['action'];
		$model=new ExamPaperQuestionModel;
		$model->attributes=$_POST['ExamPaperQuestionModel'];
		$ret = array();
		if ($action === 'add'){
			if($model->save()){
				$ret['status'] = 0;
			} else {
				$ret['status'] = 1;
				$ret['errMsg'] = CHtml::errorSummary($model);
			}
		} elseif ($action === 'remove'){
			$params = array(
				':question_id' => $model->question_id,
				':exam_paper_id' => $model->exam_paper_id,
			);
			
			$model = ExamPaperQuestionModel::model()->find('question_id=:question_id and exam_paper_id=:exam_paper_id', $params);
			if ($model && $model->delete()){
				$ret['status']=0;
			} else {
				$ret['status'] = 1;
				$ret['errMsg'] = '数据异常';
			}
		} else {
			$ret['status'] = 1;
			$ret['errMsg'] = '参数错';
			echo json_encde(array('status'=>1, 'errMsg'));
		}
		
		echo json_encode($ret);
	}
	
	public function actionSequence(){
		$id = $_POST['id'];
		$sequence = $_POST['sequence'];
		
		$model = $this->loadModel($id);
		$model->setGlobalSequence($sequence);
		// 判断序号是否有效
		$exists = ExamPaperQuestionModel::model()->exists('exam_paper_id=:epid and sequence=:seq and question_block_id=:qbid', 
			array(':epid'=>$model->exam_paper_id, ':seq'=>$model->sequence, ':qbid'=>$model->question_block_id)
		);
		if ($exists){
			$ret = array('status'=>1, 'errMsg'=>"序号$sequence上已指定题目");
		} else {
			if ($model->save()){
				$ret = array('status'=>0);
			} else {
				$ret = array('status'=>1, 'errMsg'=>CHtml::errorSummary($model));
			}
		}
		echo json_encode($ret);
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
		$criteria->with = array('question', 'question.questionExtra', 'question.questionAnswerOptions');
		$criteria->addCondition('t.exam_paper_id='.$exam_paper_id);
		$questionDataProvider = new CActiveDataProvider('ExamPaperQuestionModel', array('criteria'=>$criteria));
		
		$res['examPaperModel'] = $examPaperModel;
		$res['paperQuestionNumber'] = $paperQuestionNumber;
		$res['paperQuestionAssignNumber'] = $paperQuestionAssignNumber;
		$res['questionDataProvider'] = $questionDataProvider;
		$res['subjectModel'] = $examPaperModel->subject;
		$res['questionStatus'] = $questionStatus;
		$res['questionBlockModels'] = $questionBlockModels;
		$this->render('index', $res);
	}
	
	public function actionChoose($exam_paper_id, $exam_block_id=null, $sequence=null){
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if (!$examPaperModel)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$subjectModel = $examPaperModel->subject;
		
		$questionFilterForm = new QuestionFilterForm;
		
		$questionTypes = array (
			QuestionModel::SINGLE_CHOICE_TYPE => '单选题',
			QuestionModel::MULTIPLE_CHOICE_TYPE => '多选题',
			QuestionModel::TRUE_FALSE_TYPE => '判断题',
			QuestionModel::MATERIAL_TYPE => '材料题',
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
		$criteria->order = 'material_id, question_id desc';
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
		
		// 当前试卷已选题目
		$chosenQuestionModels = ExamPaperQuestionModel::model()->findAll('exam_paper_id='.$exam_paper_id);
		$chosenQuestions = array();
		foreach($chosenQuestionModels as $chosenQuestionModel){
			$chosenQuestions[$chosenQuestionModel->question_id] = $chosenQuestionModel;
		}
		
		$questionList = array();
		$materialIdList = array();
		foreach ($records as $record) {
			$question = $this->convertQuestionModel2Array($record);
			// 是否是被选过，是否有题号
			if (isset($chosenQuestions[$record->question_id])){
				$question['is_chosen'] = true;
				$question['is_sequenced'] = $chosenQuestions[$record->question_id]->sequence;
			}
			
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
		
		$this->render('choose', array(
			'subjectModel' => $subjectModel,
			'questionTypes' => $questionTypes,
			'examPaperListData'=>$this->getExamPaperListData($subjectModel->subject_id),
			'examPointListData' => $examPointListData,
			'questionFilterForm' => $questionFilterForm,
			'questionModel'=>$questionModel,
			'pages'=>$pager,
			'questionList'=>$questionList,
			'hideAdvancedSearch' => $hideAdvancedSearch,
			'examPaperModel' => $examPaperModel,
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
	
	private function getExamPaperListData($subject_id) {
		$examPaperModel=ExamPaperModel::model()->findAll('subject_id=:subject_id', array(':subject_id' => $subject_id));
		$examPaperListData = CHtml::listData($examPaperModel, 'exam_paper_id', 'name');
		return $examPaperListData;
	}
	
	private function convertQuestionModel2Array($questionModel) {
		$question = array();
		$question['id'] = $questionModel->question_id;
		$question['content'] = $questionModel->questionExtra->title;
		$question['analysis'] = $questionModel->questionExtra->analysis;
		if ($questionModel->question_type == QuestionModel::SINGLE_CHOICE_TYPE || $questionModel->question_type == QuestionModel::MULTIPLE_CHOICE_TYPE) {
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
		}  else if ($questionModel->question_type == QuestionModel::TRUE_FALSE_TYPE) {
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
}
