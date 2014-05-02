<?php
class NavUtil {
	public static function getNav(){
		$items = Yii::app()->cache->get(CacheKey::$NAV);
		if ($items === false){
			$items = array();
			$examBankModels = ExamBankModel::model()->findAll();
			if (empty($examBankModels)){
				return array();
			} else {
				foreach($examBankModels as $examBankModel){
					$item = array();
					$item['label'] = $examBankModel->name;
					$item['url'] = "#";
					$subjectModels = $examBankModel->subjects;
					if (!empty($subjectModels)){
						$subItems = array();
						foreach($subjectModels as $subjectModel){
							$subItem = array();
							$subItem['label'] = $subjectModel->name;
							$subItem['url'] = array('/admin/subject/view', 'id'=>$subjectModel->subject_id);
							$subItems[] = $subItem;
						}
						$item['items'] = $subItems;
					}
					$items[] = $item;
				}
			}
			$cacheDenpendency = new CGlobalStateCacheDependency(GlobalStateKey::$IS_NAV_CHANGED);
			Yii::app()->cache->set(CacheKey::$NAV, $items, 0, $cacheDenpendency);
		}
		return $items;
	}
}