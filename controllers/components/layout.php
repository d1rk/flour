<?php

class LayoutComponent extends Object
{

	public $settings = array(
	);

	var $__controller;
	
	public function initialize(&$controller, $settings = array())
	{
		$this->__controller = $controller;
	}


}

?>