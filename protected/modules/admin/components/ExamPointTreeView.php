<?php
class ExamPointTreeView extends CWidget{
	/**
	 * @var array the data that can be used to generate the tree view content.
	 * Each array element corresponds to a tree view node with the following structure:
	 * <ul>
	 * <li>text: string, required, the HTML text associated with this node.</li>
	 * <li>expanded: boolean, optional, whether the tree view node is expanded.</li>
	 * <li>id: string, optional, the ID identifying the node. This is used
	 *   in dynamic loading of tree view (see {@link url}).</li>
	 * <li>hasChildren: boolean, optional, defaults to false, whether clicking on this
	 *   node should trigger dynamic loading of more tree view nodes from server.
	 *   The {@link url} property must be set in order to make this effective.</li>
	 * <li>children: array, optional, child nodes of this node.</li>
	 * <li>htmlOptions: array, additional HTML attributes (see {@link CHtml::tag}).
	 *   This option has been available since version 1.1.7.</li>
	 * </ul>
	 * Note, anything enclosed between the beginWidget and endWidget calls will
	 * also be treated as tree view content, which appends to the content generated
	 * from this data.
	 */
	public $data;
	public $cssFile;
	public $url;
	public $animated;
	public $collapsed;
	public $control;
	public $unique;
	public $toggle;
	public $persist;
	public $cookieId;
	public $prerendered;
	public $options=array();
	public $htmlOptions;

	public function init()
	{
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$id=$this->htmlOptions['id']=$this->getId();
		if($this->url!==null)
			$this->url=CHtml::normalizeUrl($this->url);
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('treeview');
		$options=$this->getClientOptions();
		$options=$options===array()?'{}' : CJavaScript::encode($options);
		$cs->registerScript('Yii.CTreeView#'.$id,"jQuery(\"#{$id}\").treeview($options);");
		if($this->cssFile===null)
			$cs->registerCssFile($cs->getCoreScriptUrl().'/treeview/jquery.treeview.css');
		elseif($this->cssFile!==false)
			$cs->registerCssFile($this->cssFile);

		echo CHtml::tag('ul',$this->htmlOptions,false,false)."\n";
		echo self::saveDataAsHtml($this->data);
	}

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
		echo "</ul>";
	}

	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		$options=$this->options;
		foreach(array('url','animated','collapsed','control','unique','toggle','persist','cookieId','prerendered') as $name)
		{
			if($this->$name!==null)
				$options[$name]=$this->$name;
		}
		return $options;
	}

	public static function saveDataAsHtml($data)
	{
		$html='';
		if(is_array($data))
		{
			foreach($data as $node)
			{
				$model = $node['model'];
				
				if(!isset($node['text']))
					continue;

				if(isset($node['expanded']))
					$css=$node['expanded'] ? 'open' : 'closed';
				else
					$css='';

				if(isset($node['hasChildren']) && $node['hasChildren'])
				{
					if($css!=='')
						$css.=' ';
					$css.='hasChildren';
				}

				$options=isset($node['htmlOptions']) ? $node['htmlOptions'] : array();
				if($css!=='')
				{
					if(isset($options['class']))
						$options['class'].=' '.$css;
					else
						$options['class']=$css;
				}

				if(isset($node['id']))
					$options['id']=$node['id'];

				$html.=CHtml::tag('li',$options, '', false);
				
					$html.=CHtml::tag('div', array('class'=>'exam_point_tree_item'), $node['text'], false);
						$html.=CHtml::tag('span', array('class'=>'pull-right'), '', false);
							$html.=CHtml::link('增加子考点', "#", array('class'=>'add_sub_exam_point',
																		'data-id'=>$node['id'],
																		'data-name'=>$node['text']));
							$html.=' | '.CHtml::link('上移', Yii::app()->createUrl('/admin/examPoint/move', array('id'=>$model->primaryKey, 'direction'=>'up')), array('class'=>'exam_point_move_up'));
							$html.=' | '.CHtml::link('下移', Yii::app()->createUrl('/admin/examPoint/move', array('id'=>$model->primaryKey, 'direction'=>'down')), array('class'=>'exam_point_move_down'));
							$html.=' | '.CHtml::link('编辑', "#", array('class'=>'update_exam_point',
																		'data-id'=>$node['id'],
																		'data-name'=>$node['text']));
							$html.=' | '.CHtml::link('删除', Yii::app()->createUrl('/admin/examPoint/delete', array('id'=>$model->primaryKey)), 
								array('class'=>'delete_exam_point', 'data-name'=>$node['text']));
						$html.=CHtml::closeTag('span');
					
						$visibleId = 'is-visible-'.($model->primaryKey);
						$html.=CHtml::tag('label', array('class'=>'pull-right exam_point_visible'), '', false);
							$html.=CHtml::checkBox($visibleId, $model->visible, array('id'=>$visibleId, 'data-id'=>$model->primaryKey));
							$html.='前台是否显示';
						$html.=CHtml::closeTag('label');
					$html.=CHtml::closeTag('div');
				
				if(!empty($node['children']))
				{
					$html.="\n<ul>\n";
					$html.=self::saveDataAsHtml($node['children']);
					$html.="</ul>\n";
				}
				$html.=CHtml::closeTag('li')."\n";
			}
		}
		return $html;
	}

	/**
	 * Saves tree view data in JSON format.
	 * This method is typically used in dynamic tree view loading
	 * when the server code needs to send to the client the dynamic
	 * tree view data.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the JSON representation of the data
	 */
	public static function saveDataAsJson($data)
	{
		if(empty($data))
			return '[]';
		else
			return CJavaScript::jsonEncode($data);
	}
}