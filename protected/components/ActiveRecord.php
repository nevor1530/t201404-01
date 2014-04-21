<?php
/**
 * 封装了CActiveRecord，用于应用种的model继承
 * 
 * 子类需要重写getClass方法
 * @author gaosimeng
 *
 */
class ActiveRecord extends CActiveRecord {

	public $attrCreateTime='create_time';
	public $attrModifyTime='update_time';

	public function beforeSave() {
		$metaData = $this->getMetaData ();
		if ($this->getIsNewRecord ()) {
			if (isset ( $metaData->columns [$this->attrCreateTime] ) && !empty($this->attrCreateTime)) {
				$this->setAttribute($this->attrCreateTime,date('Y-m-d H:i:s'));
			}
		}
		if (isset ( $metaData->columns [$this->attrModifyTime] ) && !empty($this->attrModifyTime)) {
			$this->setAttribute($this->attrModifyTime,date('Y-m-d H:i:s'));
		}
		return parent::beforeSave ();
	}
}
