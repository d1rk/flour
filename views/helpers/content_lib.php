<?php
/**
 * ContentLibHelper
 * .
 *
 * Provides access to Content Library through a set of various functions
 *
 * @package flour
 * @category  Helpers
 * @author     Dirk Bruensicke <dirk@bruensicke.com>
 * @copyright bruensicke.com GmbH
 */
class ContentLibHelper extends AppHelper
{

	var $_Content = false;

/**
 * List of helpers used internally
 *
 * @var array
 * @access private
 */
	var $helpers = array(
		'Html',
	);

	function __construct()
	{
		$this->_init();
	}

	function get($slug, $field = null, $options = array())
	{
		return $this->_Content->get($slug, $field, $options);
	}

	function render($slug, $element = 'contents/template_default', $options = array())
	{
		$data = $this->_Content->get($slug);
		$options['data'] = $data;
		return $this->_View->element($element, $options);
	}


	function _init()
	{
		//first, check if we run with database
		if(file_exists(CONFIGS.'database.php')) //TODO: check for active connection.
		{
			uses('model' . DS . 'connection_manager'); //TODO: check, if we need this line
			$db = ConnectionManager::getInstance();
			$connected = $db->getDataSource('default');

			if ($connected->isConnected())
			{
				if(!$this->_Content)
				{
					$this->_Content = ClassRegistry::init('Flour.Content');
				}
			}
		}
		$this->_View = ClassRegistry::init('View', 'ThemeView');
	}




}
?>
