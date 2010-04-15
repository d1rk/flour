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

	var $size = 16;

	var $classes = array('ui-button', 'ui-state-default', 'ui-corner-all');

	var $white = '/flour/img/white/:size/:ico.png';
	var $black = '/flour/img/black/:size/:ico.png';

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
 * creates a link (with white icon, if given)
 *
 * @return array
 * @access public
 */
	function white($name, $link = array(), $options = array())
	{
		$ico = $this->ico;
		$classes = $this->classes;
		$this->classes = array();
		$this->ico = $this->white;
		$output = $this->link($name, $link, $options);
		$this->ico = $ico;
		$this->classes = $classes;
		return $output;
	}

/**
 * creates a link (with black icon, if given)
 *
 * @return array
 * @access public
 */
	function black($name, $link = array(), $options = array())
	{
		$ico = $this->ico;
		$classes = $this->classes;
		$this->classes = array();
		$this->ico = $this->black;
		$output = $this->link($name, $link, $options);
		$this->ico = $ico;
		$this->classes = $classes;
		return $output;
	}

/**
 * creates a link (with icon, if given)
 *
 * @return array
 * @access public
 */
	function link($name, $link = array(), $options = array())
	{
		$defaults = array(
			'class' => $this->classes,
		);

		if(!isset($options['class']))
		{
			$options['class'] = '';
		}

		if(!is_array($options['class']))
		{
			$options['class'] = array($options['class']);
		}

		if(isset($options['ico']))
		{
			if(!isset($options['size']))
			{
				$options['size'] = $this->size;
			}
			$img = $this->image($options);
			unset($options['ico']);
			unset($options['size']);
		} else {
			$img = null;
		}

		$options['class'] = implode(' ', array_merge($defaults['class'], $options['class']));
		
		$options['escape'] = false;
		
		if(!empty($img))
		{
			$name = $this->Html->tag('span', $img).$this->Html->tag('span', $name, array('class' => 'ui-button-text'));
		}
		$output = $this->Html->link($name, $link, $options);
		return $this->output($output);
	}

	function button($name, $options = array())
	{
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

		if(isset($options['ico']))
		{
			$img = $this->Html->image(String::insert($this->ico, array('ico' => $options['ico'])), array('width' => '16', 'height' => '16'));
			unset($options['ico']);
		} else {
			$img = null;
		}

		$options['class'] = implode(' ', array_merge($defaults['class'], $options['class']));
		
		$options['escape'] = false;
		
		$output = $this->Html->tag('button', $this->Html->tag('span', $img).$this->Html->tag('span', $name, array('class' => 'ui-button-text')), $options);
		return $this->output($output);
	}

	function image($options)
	{
		$img = $this->Html->image(
			String::insert(
				$this->ico, array(
					'ico' => $options['ico'],
					'size' => $options['size']
				)
			),
			array('width' => $options['size'], 'height' => $options['size'])
		);
		return $img;
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
