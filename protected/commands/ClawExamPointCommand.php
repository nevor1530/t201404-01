<?php
include_once(dirname(__FILE__).'/../extensions/simple_html_dom.php');

class ClawExamPointCommand extends CConsoleCommand
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
	
	private function load($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);	// 当根据Location:重定向时，自动设置header中的Referer:信息
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);	// 在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);	// 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$cookie = file_get_contents(dirname(__FILE__).'/cookie');
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		
		$html = curl_exec($ch);
		return $html;
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