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

	function setup()
	{
		debug($this->params);
		$this->__controller->theme = Configure::read('App.theme');
		if (!empty($this->__controller->params['prefix']) && in_array($this->__controller->params['prefix'], Configure::read('Routing.prefixes')))
		{
			$this->__controller->layout = $this->__controller->params['prefix']; //'admin'; //set admin-layout for admin-routes.
		}
	}
}

?>