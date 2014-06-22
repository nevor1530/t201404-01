<?php
class ClawExamPointCommand extends ClawCommand
{
	const LEVEL_REG = '/keypoint-level-(\d)/';
	
	private $_subject_id = null;
	
	public function actionClaw($url, $subject_id)
	{
		$this->_subject_id = $subject_id;
		
		$raw = $this->load($url);
		$html = str_get_html($raw);
		
		$doms = $html->find('.keypoint-level-0');
		if (!empty($doms)){
			$this->parse($doms[0], 0);
		} else {
			echo '网页结构不对';
		}
	}
	
	private function parse(&$dom, $pid){
		$level = $this->level($dom->class);
		while($dom !== null){
			$textDoms = $dom->find('.name-col .text');
			$model = new ExamPointModel();
			// name
			foreach ($dom->find('.text', 0)->find('text') as $text){
				$text = trim($text);
				$text = trim($text, '－');
				if (!empty($text)){
					$model->name = $text;
					break;
				}
			}
			
			$model->pid = $pid;
			$model->subject_id = $this->_subject_id;
			if ($model->save()){
				echo $model->name.' pid:'.$model->pid."\n";
				$dom = $dom->next_sibling();
				if ($dom !== null){
					$nextLevel = $this->level($dom->class);
					if ($nextLevel == $level){
						continue;
					} elseif ($nextLevel > $level) {
						$this->parse($dom, $model->primaryKey);
						if ($dom !== null){
							$nextLevel = $this->level($dom->class);
							if ($nextLevel < $level){
								return;
							}
						}
					} else {
						return;
					}
				}
			} else {
				throw new Exception(CHtml::errorSummary($model));
			}
		}
	} 

	private function level($str){
		$matches = array();
		if (preg_match(self::LEVEL_REG, $str, $matches)){
			if (count($matches) >=2 ){
				return $matches[1];
			}
		}
		throw new Exception("class $str has no pattern ".self::LEVEL_REG);
	}
}