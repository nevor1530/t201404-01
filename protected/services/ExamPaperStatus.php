<?php
class ExamPaperStatus {
	public static function refresh($exam_paper_id){
		$examPaperModel = ExamPaperModel::model()->findByPk($exam_paper_id);
		if (!$examPaperModel){
			throw new Exception('exam_paper_id '.$exam_paper_id.' not exists');
		}
		$questionBlockModels = $examPaperModel->questionBlocks;
		// 如果没有question block，则肯定是未录完状态
		if (!$questionBlockModels){
			if ($examPaperModel->status !== ExamPaperModel::STATUS_UNCOMPLETE){
				$examPaperModel->status = ExamPaperModel::STATUS_UNCOMPLETE;
				$examPaperModel->save();
			}
		} else {
			$is_full = true;	// 是否已录完
			// 遍历所有的question blocks
			foreach($questionBlockModels as $questionBlockModel){
				// 如果block下的题目数量和设置的题数不一样，就是未录完
				$count = QuestionModel::model()->count('exam_block_id='.$questionBlockModel->exam_block_id);
				if ($questionBlockModel->question_number > $count){
					if ($examPaperModel->status !== ExamPaperModel::STATUS_UNCOMPLETE){
						$examPaperModel->status = ExamPaperModel::STATUS_UNCOMPLETE;
						$examPaperModel->save();
					}
					$is_full = false;
					break;
				}
			}
			// 如果是录完的，且当前状态是未录完，则改为未发布
			if ($is_full){
				if ($examPaperModel->status === ExamPaperModel::STATUS_UNCOMPLETE){
					$examPaperModel->status = ExamPaperModel::STATUS_UNPUBLISHED;
					$examPaperModel->save();
				}
			}
		}
	}
}