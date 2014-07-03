<?php
class ClawExamPaperCommand extends ClawCommand
{
	// TODO 记录涉及的数据库的最大值id，如果后续出问题，直接把后插入的所有记录都删除掉就可以
	private $_max_exam_paper_id;
	private $_max_question_block_id;
	private $_max_question_id;
	
	/**
	 * @param subject_id, 本地数据库的课程ID, example value: 0
	 * @param course 猿题库的课程, example value: 'xingce'
	 * @param $url 练习历史列表页, example value: "http://yuantiku.com/{course}/history"
	 */
	public function actionClaw($subject_id, $course, $url){
		$this->recordMaxId();
		
		$url = str_replace('{course}', $course, $url);
		
		// 获取试卷的url集合
		$urls = $this->get_paper_urls($url);
		
		// 爬网页面
		foreach($urls as $url){
			$this->parse_paper($url, $course, $subject_id);
		}
	}
	
	
	//////////////////////// 方法 //////////////////////
	/**
	 * 解析列表页，返回试卷的url集合
	 */
	private function get_paper_urls($url){
		// 列表页
		$raw = $this->load($url);
		$html = str_get_html($raw);
		// check 是否有效登录
		if (!$this->check_login($html)){
			display('未登录');
			exit();
		}
		
		$doms = $html->find('.btn-inner');
		$urls = array();
		foreach($doms as $dom){
			$text = $dom->plaintext;
			// 保证文件和网页是utf-8的才行
			if (strpos($text, '查看解析') !== false){
				$parent = $dom->parent();
				$urls[] = 'http://yuantiku.com'.$this->dom_data($parent, 'href');
			}
		}
		if (empty($urls)){
			throw new Exception('解析出0个试卷地址，检查dom结构是否变化');
		} else {
			return $urls;
		}
	}
	
	/**
	 * 解析试卷
	 * @param url
	 * @param subject_id 本地数据库中课程的id
	 * @param course 爬取课程名称
	 */
	private function parse_paper($url, $course, $subject_id){
		$this->display('开始解析：'.$url);
		$raw = $this->load($url);
		$html = str_get_html($raw);
		// 试卷属性
		$titleDom = $html->find('.exercise-solution-wrap', 0);
		$title = $this->dom_data($titleDom, 'sheet-name');
		$exercise_id = $this->dom_data($titleDom, 'exercise-id');
		$this->display('claw paper: '.$title);
	
		// TODO 保存试卷到数据库
		$paperModel = new ExamPaperModel();
		$paperModel->subject_id = $subject_id;
		$paperModel->name = $title;
		$paperModel->shortName = $title;
		if (!$paperModel->save()){
			$this->failed();
		}
		
		// $paper_id
		$paper_id = $paperModel->primaryKey;
		
		// 解析模块
		$blockDoms = $html->find('div.exercise-hd ul.nav a.chapter-switch');
		$blocks = array();
		foreach($blockDoms as $dom){
			$text = $dom->plaintext;
			// text is formed like '常识判断				[0 \35]', so it should be cut
			$reg = '/^(?<name>\S+)(?:\s+)\[\s*\d+\s*\/\s*(?<questions>\d+)\s*\]/';
			if (preg_match($reg, $text, $matches)){
				$blockName = $matches['name'];
				$questions = $matches['questions'];
				$blockIndex = $this->dom_data($dom, 'chapter-index');
			} else {
				throw new Exception('模块'.$text.'解析出错');
			}
	
			// TODO save block
			// $block_id
			$block_id = 0;
			echo $blockName.' '.$questions."\n";
	
			// parse block
			$url = 'http://yuantiku.com/api/{course}/exercises/{exerciseId}/solutions/html?chapterIndex={chapterIndex}';
			$url = str_replace(array('{course}', '{exerciseId}', '{chapterIndex}'), array($course, $exercise_id, $blockIndex), $url);
			echo '开始解析模块'.$blockName." $url\n";
			$this->parse_block($url, $block_id, $paper_id);
		}
		// TODO 调试用，调通后删除
		exit();
	}
	
	/**
	 * 解析模块页面
	 */
	private function parse_block($url, $block_id, $paper_id){
		$raw = $this->load($url);
		$html = str_get_html($raw);
		$children = $html->find('.chapter-wrap', 0)->children();
		foreach($children as $child){
			// 根据class判断是是否判断题
			$class = $child->class;
			if (!$class){
				continue;
			} else {
				if (strpos($class, 'question-wrap') !== false){
					// 非材料题
					$this->parse_question($child, $block_id, $paper_id); 
				} elseif (strpos($class, 'material-wrap') !== false){
					// 材料题
					$this->parse_material($child, $block_id, $paper_id);
				}
			}
		}
	}
	
	private function failed(){
		exit();
	}
	
	/**
	 * 保存已有的最大ID，方便后面如果出错了，可以删除无效数据
	 */
	private function recordMaxId(){
		$sql = 'select max(exam_paper_id) as maxid from exam_paper';
		$this->_max_exam_paper_id = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = 'select max(question_block_id) as maxid from question_block';
		$this->_question_block_id = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = 'select max(question_id) as maxid from question';
		$this->_question_id = Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	/**
	 * 调试用，无实际逻辑功能
	 */
	private function save($content){
		$fp = fopen('download.html', 'w');
		fwrite($fp, $content);
		fclose($fp);
	}
	
	/**
	 * 调试用，无实际逻辑功能
	 */
	private function str2hex($str){
		for($i=0; $i < strlen($str); $i++){
			echo ' '.dechex(ord($str[$i]));
		}
		echo "\n";
	}
	
	/**
	 * 获取dom节点上形如data-{attribute}样式的属性
	 */
	private function dom_data($dom, $attr){
		$a = 'data-'.$attr;
		return $dom->$a;
	}
	
	/**
	 * 控制台输出前转码
	 */
	private function display($str){
		echo mb_convert_encoding($str, 'gbk', 'utf-8')."\n";
//		echo $str."\n";
	}
}