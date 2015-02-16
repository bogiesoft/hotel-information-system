<?php
/**
 * CLocationPicker class file.
 *
 * @author Febrianto Arif <febrianto.arif@live.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CLocationPicker displays a map using Leaflet.js
 *
 * This widget will return the output of 2 hidden textfield : lat & lon
 * These textfield will contains the coordinate of current location.
 *
 * <pre>
 * $this->widget('zii.widgets.CLocationPicker', array(
 *			'latId' => "lat",
 *			'lonId' => "lon",
 *     ),
 * ));
 * </pre>
 *
 *
 * @author Febrianto Arif <febrianto.arif@live.com>
 * @package zii.widgets
 * @since 1.0
 */
class CLocationPicker extends CWidget
{
	public $latId = "latitude";
	public $lonId = "longitude";
	public $height = "250px";
	public $model;
	
	/**
	 * Initializes the menu widget.
	 * This method mainly normalizes the {@link items} property.
	 * If this method is overridden, make sure the parent implementation is invoked.
	 */
	public function init()
	{
		echo CHtml::openTag('div', array("id"=>"divsearch"));
		echo CHtml::tag("input", array("id"=>"searchtext", "type"=>"text"));
		echo CHtml::openTag("button", array("id"=>"searchbutton", "class"=>"btn btn-primary"));
		echo "Cari";
		echo CHtml::closeTag('button');
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array("id"=>"clocationmap", "style"=>"height:".$this->height))."\n";
		echo CHtml::closeTag('div');
		
		$randNumber = rand(0, 100000);
		$className = get_class($this->model);
		
		echo CHtml::hiddenField($className."[".$this->latId."]", $this->model->latitude, array("class"=>"lat_".$randNumber));
		echo CHtml::hiddenField($className."[".$this->lonId."]", $this->model->longitude, array("class"=>"lon_".$randNumber));
		
		echo CHtml::openTag('script',array("src"=>"https://maps.googleapis.com/maps/api/js?key=AIzaSyAtc_4SE2BhMel6_WVpSBAjAeF1iczXUow&sensor=false"))."\n";
		echo CHtml::closeTag('script');
		
		echo CHtml::openTag('style');
		echo "#clocationmap img { max-width: none; }
			#divsearch { text-align : right }
			#divsearch input { margin : 5px }";
		echo CHtml::closeTag('style');
		
		ob_start();
		include("picker.js");
		$picker = ob_get_clean();
		
        echo CHtml::openTag('script');
        echo $picker;
        echo CHtml::closeTag('script');
        
		//Yii::app()->getClientScript()->registerScript('CLocationPicker',$picker);
	}

	/**
	 * Calls {@link renderMenu} to render the menu.
	 */
	public function run()
	{
		
	}
}