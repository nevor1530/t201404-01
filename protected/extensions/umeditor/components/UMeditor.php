<?php
class UMeditor extends CApplicationComponent
{
	/**
	 * @var boolean indicates whether assets should be republished on every request.
	 */
	public $forceCopyAssets = false;

	protected $_assetsUrl;

	public function register()
	{
		Yii::app()->clientScript->registerCoreScript('jquery');
		$filename = YII_DEBUG ? 'umeditor.js' : 'umeditor.min.js';
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/'.$filename);
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/umeditor.config.js');
		
		$cssname = YII_DEBUG ? 'umeditor.css' : 'umeditor.min.css';
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/themes/default/css/'.$cssname);
	}

	protected function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('umeditor.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, $this->forceCopyAssets);
			return $this->_assetsUrl = $assetsUrl;
		}
	}

    public function getVersion()
    {
        return '1.0.0';
    }
}
