<?php
/*
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CDataColumn represents a grid view column that is associated with a data attribute or PHP expression.
 *
 * Either {@link name} or {@link value} should be specified. The former specifies
 * a data attribute name, while the latter a PHP expression whose value should be rendered instead.
 *
 * The property {@link sortable} determines whether the grid view can be sorted according to this column.
 * Note that the {@link name} should always be set if the column needs to be sortable. The {@link name}
 * value will be used by {@link CSort} to render a clickable link in the header cell to trigger the sorting.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package zii.widgets.grid
 * @since 1.1
 */
class NDataLinkColumn extends CDataColumn
{
	public $url;
	public $urlExpression;
	public $linkHtmlOptions;

	/**
	 * Renders the data cell content.
	 * This method evaluates {@link value} or {@link name} and renders the result.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		elseif($this->name!==null)
			$value=CHtml::value($data,$this->name);
		$label =  $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
		
		
		if($this->urlExpression!==null)
			$url=$this->evaluateExpression($this->urlExpression,array('data'=>$data,'row'=>$row));
		else
			$url=$this->url;
		$options=$this->linkHtmlOptions;
		echo CHtml::link($label,$url,$options);
	}
}
