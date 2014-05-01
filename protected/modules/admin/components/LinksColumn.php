<?php
class LinksColumn extends CLinkColumn {
	
	public $name;
	
	protected function renderDataCellContent($row,$data)
	{
		$items = CHtml::value($data, $this->name);
		if (empty($items)){
			echo '<无>';
		} else {
			$arr = array();
			foreach($items as $item){
				if($this->urlExpression!==null)
					$url=$this->evaluateExpression($this->urlExpression,array('data'=>$item,'row'=>$row));
				else
					$url=$this->url;
				$arr[] = CHtml::link($item->name, $url, $this->linkHtmlOptions);
			}
			echo implode($arr);
		}
	}
}