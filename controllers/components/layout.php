<?php
/**
 * LayoutComponent
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class LayoutComponent extends Object
{
	public $settings = array(

	);

	var $view = 'Flour.Theme';

	var $components = array(
		'Session',
		'Cookie',
		'RequestHandler',
		'Flour.Flash',
	);

	var $__controller;

	var $__flourHelpers = array(
		'Session',
		'Html',
		'Form',
		'Text',
		'Time',
		'Flour.Grid',
		'Flour.Button',
		'Flour.Nav',
	);
	
	var $__flourComponents = array(
		'Session',
		'Cookie',
		'RequestHandler',
		'Flour.Flash',
	);
	
	public function initialize(&$controller, $settings = array())
	{
		$this->__controller = $controller;
		$this->setup();
	}

	function setup()
	{
		$admin_theme = Configure::read('Admin.theme');
		if(empty($admin_theme)) {
			Configure::write('Admin.theme', 'default');
		}
		$this->__controller->view = $this->view;
		$this->__controller->theme = Configure::read('App.theme');

		if (!empty($this->__controller->params['prefix']) && in_array($this->__controller->params['prefix'], Configure::read('Routing.prefixes')))
		{
			$this->__controller->layout = $this->__controller->params['prefix']; //'admin'; //set admin-layout for admin-routes.
		}
	}

	function startup()
	{
		$this->checkController();
	}

	function checkController()
	{
		$this->__controller->helpers = array_merge($this->__controller->helpers, $this->__flourHelpers);

		$loaded = array_keys($this->__controller->Component->_loaded);
		foreach($loaded as $component)
		{
			if(!isset($this->__controller->$component))
			{
				$this->__controller->$component = $this->__controller->Component->_loaded[$component];
			}
		}
	}
}

?>