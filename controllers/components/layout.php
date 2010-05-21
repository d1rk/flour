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
		Configure::write('Admin.theme', 'default');
		$this->__controller->view = $this->view;
		$this->__controller->theme = Configure::read('App.theme');

		$this->checkController();

		if (!empty($this->__controller->params['prefix']) && in_array($this->__controller->params['prefix'], Configure::read('Routing.prefixes')))
		{
			$this->__controller->layout = $this->__controller->params['prefix']; //'admin'; //set admin-layout for admin-routes.
		}
	}

	function checkController()
	{
		$this->__controller->helpers = array_merge($this->__controller->helpers, $this->__flourHelpers);
		$this->__controller->components = array_merge($this->__controller->components, $this->__flourComponents);
	}
}

?>