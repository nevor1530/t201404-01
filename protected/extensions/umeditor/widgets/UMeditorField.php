<?php 
class UMeditorField extends CWidget {
	
	public $model;
	public $name;
	public $width = "1000px";
	public $height = "240px";
	
	public function init()
	{
		
	}
	
	public function run()
	{
		$inputName = CHtml::resolveName($this->model, $this->name); 
		$id = CHtml::getIdByName($inputName);
		
		$htmlOptions = array('type'=>'text/plain', 'id'=>$id, 'style'=>"width:$this->width;height:$this->height;");
		CHtml::resolveNameID($this->model,$this->name,$htmlOptions);
		echo CHtml::tag('script',$htmlOptions, false, false);
		echo $this->model->getAttribute($this->name);
		echo CHtml::closeTag('script');
		
		$cs = Yii::app()->getClientScript();
		$script = <<< END
	var {$id}_option = {
		name: "$id"
	};
	var $id = UM.getEditor('$id', {$id}_option);
END;

		$cs->registerScript($id, $script);
	}
}