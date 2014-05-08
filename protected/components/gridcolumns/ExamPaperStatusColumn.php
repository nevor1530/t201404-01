<?php
/**
 * 专门为试卷管理的状态栏定制的
 */
class ExamPaperStatusColumn extends MapColumn
{
	public function init()
	{
		$script = <<< END
		jQuery(".js_exam_paper_publish_btn").live('click', function(e){
			jQuery.post(
				this.href,
				function(data){
					location.reload();
				},
				'json'
			);
			return false;
		});
		
		jQuery(".js_exam_paper_cancel_btn").live('click', function(e){
			jQuery.post(
				this.href,
				function(data){
					location.reload();
				},
				'json'
			);
			return false;
		});
END;
		Yii::app()->clientScript->registerScript(__CLASS__.'#status', $script);
		parent::init();
	}
	
	/**
	 * Renders the data cell content.
	 * This method evaluates {@link value} or {@link name} and renders the result.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		$value=CHtml::value($data,$this->name);
		echo $this->mapData[$value];
		switch ($value){
		case ExamPaperModel::STATUS_UNCOMPLETE:
			break;
		case ExamPaperModel::STATUS_UNPUBLISHED:
			echo ' | ';
			echo CHtml::link('发布', 
							Yii::app()->createUrl('/admin/examPaper/publish', array('id'=>$data->primaryKey)), 
							array('class'=>'js_exam_paper_publish_btn'));
			break;
		case ExamPaperModel::STATUS_PUBLISHED:
			echo ' | ';
			echo CHtml::link('取消', 
							Yii::app()->createUrl('/admin/examPaper/cancel', array('id'=>$data->primaryKey)), 
							array('class'=>'js_exam_paper_cancel_btn'));
			break;
		default:
			throw new Exception('unknow status:'. $value);
			break;
		}
	}
}
