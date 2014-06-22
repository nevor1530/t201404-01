<?php
class ClawExamPaperCommand extends ClawCommand
{
	
	private $_subject_id = null;
	
	public function actionClaw($url, $subject_id)
	{
		// 列表页，解析出所有试卷的地址
		$paperUrls = $this->parseList($url);
		// 解析题目，创建试卷
		// 解析模块，获取模块地址，创建模块
		// 遍历题目，解析题目
	}
}