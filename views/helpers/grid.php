<?php
/**
 * GridHelper.
 *
 * Provides access to blueprint specific functions
 *
 * PHP versions 5
 *
 * @category  Helpers
 * @author     Dirk Bruensicke <dirk@bruensicke.com>
 * @copyright  2009 Dirk Bruensicke
 * @license    http://www.opensource.org/licenses/mit-license.php   The MIT License
 * @link      http://bruensicke.com/code
 */
class GridHelper extends AppHelper
{

	var $connector = '';

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
 * opens a grid-system in the view
 *
 * @return array
 * @access public
 */
	function open($span = '24')
	{
		$output = array();
		$output[] = $this->Html->div('container');
		$output[] = $this->Html->div('span-'.$span);
		return $this->output($output);
	}

/**
 * closes a grid-system in the view
 * 
 * @access public
 */
	function close()
	{
		$output = array();
		$output[] = $this->Html->tag('/div'); //div.span-X
		$output[] = $this->Html->tag('/div'); //div.container
		return $this->output($output);
	}

/**
 * returns a div with set span
 *
 * @param int $span width of span
 * @param mixed $content bool for 'last' or string for content of div
 * @param array $options Options to be given into div
 * @access public
 */
	function span($span = 24, $content = null, $options = array())
	{
		$output = array();
		if(is_bool($content))
		{
			$class = "span-$span last";
			$content = null;
		} else {
			$class = "span-$span"
		}
		$output[] = $this->Html->div('span-'.$span, $content, $options);
		return $this->output($output);
	}

/**
 * returns a /div
 *
 * @access public
 */
	function end()
	{
		$output = array();
		$output[] = $this->Html->tag('/div'); //TODO: Calculate correct span and output number
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
