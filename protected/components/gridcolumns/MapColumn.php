<?php
/**
 * 扩展GridView里Map类型表现形式，参数$mapData必须指定且为数组
 */
class MapColumn extends CDataColumn
{
	public $mapData;
	/**
	 * @var boolean whether the column is sortable. If so, the header cell will contain a link that may trigger the sorting.
	 * Defaults to true. Note that if {@link name} is not set, or if {@link name} is not allowed by {@link CSort},
	 * this property will be treated as false.
	 * @see name
	 */
	public $sortable=false;

	protected function renderFilterCellContent()
	{
		echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->mapData, array('id'=>false,'prompt'=>''));
	}

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
		echo $value===null ? $this->grid->nullDisplay : $this->mapData[$value];
	}
}
