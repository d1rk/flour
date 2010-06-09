<?php

class CustomListHelper extends AppHelper
{
	var $helpers = array(
		'Html',
	);
	
	function show($data = array(), $options = array()) {
		if(is_string($data)) {
			$data = $this->get($data, $options);
		}
		$output = array();
		
		foreach($data as $key => $val) {
			$output[] = $this->Html->tag('dt', $key);
			$output[] = $this->Html->tag('dd', $val);
		}
		
		$output = $this->Html->tag('dl', implode($output));
		
		return $output;
	}

	function get($slug, $options = array()) {
		return array();
	}

	// function _init()
	// {
	// 	//first, check if we run with database
	// 	if(false && file_exists(CONFIGS.'database.php')) //TODO: check for active connection.
	// 	{
	// 		uses('model' . DS . 'connection_manager'); //TODO: check, if we need this line
	// 		$db = ConnectionManager::getInstance();
	// 		$connected = $db->getDataSource('default');
	// 
	// 		if ($connected->isConnected())
	// 		{
	// 			if (!class_exists($this->navModel))
	// 			{
	// 				App::import('Model', $this->navModel);
	// 				if(!class_exists($this->navModel)) App::import('Model', $this->pluginName.'.'.$this->navModel); //no Model there, must be plugin-use
	// 			}
	// 			if(!$this->navObject)
	// 			{
	// 				$this->navObject = new $this->navModel();
	// 			}
	// 		}
	// 	}
	// }
	// 
}
?>