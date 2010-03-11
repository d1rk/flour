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
class ThemeView extends View {
/**
 * Constructor for ThemeView sets $this->theme.
 *
 * @param Controller $controller
 */
	function __construct(&$controller, $register = true) {
		parent::__construct($controller, $register);
		$this->theme =& $controller->theme;
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
 *
 * @param string $name Name of template file in the/app/views/elements/ folder
 * @param array $params Array of data to be made available to the for rendered
 *    view (i.e. the Element)
 * @return string Rendered Element
 * @access public
 */
	function element($name, $params = array(), $loadHelpers = false) {
		if(!isset($params['plugin'])) $params['plugin'] = 'flour'; //set plugin to flour, if nothing was given
		return parent::element($name, $params, $loadHelpers);
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
		$paths = parent::_paths($plugin, $cached);
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
		}
		return $paths;
	}
}
?>