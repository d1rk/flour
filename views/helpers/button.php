<?php
/**
 * ButtonHelper.
 *
 * jQuery UI conform action-buttons
 *
 * PHP versions 5
 *
 * @category  Helpers
 * @author     Dirk Bruensicke <dirk@bruensicke.com>
 * @copyright  2009 Dirk Bruensicke
 * @license    http://www.opensource.org/licenses/mit-license.php   The MIT License
 * @link      http://bruensicke.com/code
 */
class ButtonHelper extends AppHelper
{

	var $connector = '';

	var $ico = '/flour/img/ico/:ico.png';

/**
 * List of helpers used internally
 *
 * @var array
 * @access private
 */
	var $helpers = array(
		'Html',
	);

/**
 * creates a link (with icon, if given)
 *
 * @return array
 * @access public
 */
	function link($name, $link = array(), $options = array())
	{
		$output = array();
		$defaults = array(
			'class' => array('ui-button', 'ui-state-default', 'ui-corner-all'),
		);

		if(!isset($options['class']))
		{
			$options['class'] = '';
		}

		if(!is_array($options['class']))
		{
			$options['class'] = array($options['class']);
		}

		$img = (isset($options['ico']))
			? $this->Html->image(String::insert($this->ico, array('ico' => $options['ico'])))
			: null;

		$options['class'] = implode(' ', array_merge($defaults['class'], $options['class']));
		
		$options['escape'] = false;
		
		$output = $this->Html->link($this->Html->tag('span', $img).$this->Html->tag('span', $name, array('class' => 'ui-button-text')), $link, $options);
		return $this->output($output);
	}

	function output($lines = '')
	{
		if(!is_array($lines))
		{
			$lines = array($lines);
		}
		return implode($this->connector, $lines);
	}

}
?>
