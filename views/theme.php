<?php
/**
 * A custom view class that is used for themeing
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Theme view class
 *
 * Allows the creation of multiple themes to be used in an app. Theme views are regular view files
 * that can provide unique HTML and static assets.  If theme views are not found for the current view
 * the default app view files will be used. You can set `$this->theme` and `$this->view = 'Theme'` 
 * in your Controller to use the ThemeView.
 *
 * Example of theme path with `$this->theme = 'super_hot';` Would be `app/views/themed/super_hot/posts`
 *
 * @package       cake
 * @subpackage    cake.cake.libs.view
 */
class ThemeView extends View
{
	public $title;

	var $description = null;

	var $admin = false;

	var $slots = array();

	var $debugMode = false;

	public $_crumbs = array();
	
/**
 * Constructor for ThemeView sets $this->theme.
 *
 * @param Controller $controller
 */
	function __construct(&$controller, $register = true)
	{
		parent::__construct($controller, $register);
		$this->_controller = $controller;
		$this->admin = (isset($this->params['admin']) && $this->params['admin'])
			? true
			: false;
		$this->title = $this->name;
		$this->theme = ($this->admin)
			? Configure::read('Admin.theme')
			: $controller->theme;
	}

/**
 * Adds a link to the breadcrumbs array.
 *
 * @param string $name Text for link
 * @param string $link URL for link (if empty it won't be a link)
 * @param mixed $options Link attributes e.g. array('id'=>'selected')
 * @return void
 * @see HtmlHelper::link() for details on $options that can be used.
 * @access public
 */
	function addCrumb($name, $link = null, $options = null) {
		$this->_crumbs[] = array($name, $link, $options);
	}

/**
 * Returns the breadcrumb trail as an unordered list of links.
 *
 * @param string $startText This will be the first crumb, if false it defaults to first crumb in array
 * @return string Composed bread crumbs
 * @access public
 */
	function getCrumbs($startText = false) {
		if (!empty($this->_crumbs)) {
			$out = array();
			if ($startText) {
				$out[] = $this->Html->link($startText, '/');
			}

			foreach ($this->_crumbs as $crumb) {
				if (!empty($crumb[1])) {
					$out[] = $this->Html->link($crumb[0], $crumb[1], $crumb[2]);
				} else {
					$out[] = $crumb[0];
				}
			}
			return $out;
		} else {
			return null;
		}
	}

	function widget($type, $template = 'default', $options = array())
	{
		return $this->element('widget', array(
			'type' => $type,
			'template' => $template,
			'widget_data' => $options,
		));
	}


/**
 * Renders a piece of PHP with provided parameters and returns HTML, XML, or any other string.
 *
 * This realizes the concept of Elements, (or "partial layouts")
 * and the $params array is used to send data to be used in the
 * Element.  Elements can be cached through use of the cache key.
 *
 * This method has been overwritten to extend the plugin, to look for, it no plugin is given
 * 
 * ### Special params
 *
 * - `cache` - enable caching for this element accepts boolean or strtotime compatible string.
 *   Can also be an array. If `cache` is an array,
 *   `time` is used to specify duration of cache.
 *   `key` can be used to create unique cache files.
 * - `plugin` - Load an element from a specific plugin.
 * - `target` - save output into named slot
 *
 * @param string $name Name of template file in the/app/views/elements/ folder
 * @param array $params Array of data to be made available to the for rendered
 *    view (i.e. the Element)
 * @return string Rendered Element
 * @access public
 */
	function element($name, $params = array(), $loadHelpers = false) {
		if(!isset($params['plugin'])) $params['plugin'] = 'flour'; //set plugin to flour, if nothing was given

		if(isset($params['target']))
		{
			$target = $params['target'];
			unset($params['target']);
		}

		$output = parent::element($name, $params, $loadHelpers);

		if(isset($target))
		{
			//TODO: check target
			$this->slots[$target] = $output;
		}

		if(Configure::read() && $this->debugMode)
		{
			return "\r\r\r<!-- ELEMENT[$name]-->\r\t<div class=\"element\" title=\"$name\">$output</div>\r\r\r";
		}
		return $output;
	}

	function render($action = null, $layout = null, $file = null)
	{
		if(Configure::read() && $this->viewPath == 'errors')
		{
			$this->plugin = 'flour';
			$this->layout = 'error';
		}
		return parent::render($action, $layout, $file);
	}

	//TODO: use for something useful
	function _render($___viewFn, $___dataForView, $loadHelpers = true, $cached = false) {
		return parent::_render($___viewFn, $___dataForView, $loadHelpers, $cached);
	}

/**
 * Return all possible paths to find view files in order
 *
 * @param string $plugin
 * @param boolean $cached Set to true to force dir scan.
 * @return array paths
 * @access protected
 * @todo Make theme path building respect $cached parameter.
 */
	function _paths($plugin = null, $cached = true) {
		if ($plugin === null && $cached === true && !empty($this->__paths)) {
			return $this->__paths;
		}
		$paths = array();
		$viewPaths = App::path('views');
		$corePaths = array_flip(App::core('views'));

		$paths = array_merge($paths, $viewPaths);

		$themePaths = array();

		if (!empty($this->theme)) {
			$count = count($paths);
			for ($i = 0; $i < $count; $i++) {
				if (strpos($paths[$i], DS . 'plugins' . DS) === false
					&& strpos($paths[$i], DS . 'libs' . DS . 'view') === false) {
						if ($plugin) {
							//$themePaths[] = $paths[$i] . 'themed'. DS . $this->theme . DS . 'plugins' . DS . $plugin . DS;
							$themePaths[] = ROOT.DS.'themes'. DS . $this->theme . DS . 'plugins' . DS . $plugin . DS;
						}
						//$themePaths[] = $paths[$i] . 'themed'. DS . $this->theme . DS;
						$themePaths[] = ROOT.DS.'themes'. DS . $this->theme . DS;
					}
			}
			$lastone = array_pop($paths); //move last path (hopefully the cake-core) ...
			$paths = array_merge($paths, $themePaths); // (merge theme-path in)
			$paths[] = $lastone; //... to the last place in array
			
			//append plugin-paths later on
			if (!empty($plugin)) {
				$count = count($viewPaths);
				for ($i = 0; $i < $count; $i++) {
					if (!isset($corePaths[$viewPaths[$i]])) {
						$paths[] = $viewPaths[$i] . 'plugins' . DS . $plugin . DS;
					}
				}
				$paths[] = App::pluginPath($plugin) . 'views' . DS;
			}
		}
		return $paths;
	}
}
?>