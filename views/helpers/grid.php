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

	var $last = false;

	var $row = 0;

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
		$this->calculate($span);
		if(is_bool($content) )
		{
			$class = "span-$span last";
			$content = null;
		} else {
			$class = "span-$span";
		}
		return $this->output($this->Html->div($class, $content, $options));
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

	function calculate($span)
	{
		$this->row = $this->row + $span;
		if($this->row >= 24)
		{
			$this->last = true;
			$this->row = 0;
		}
		return $this->last;
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
