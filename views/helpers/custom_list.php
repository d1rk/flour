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
		
		if(is_bool($options) && $options) {
			foreach($data as $term => $value) {
				$def = is_array($value) 
					? $value[0] 
					: $value;
				$def_opt = is_array($value)
					? $value[1]
					: array();

				$output[] = $this->Html->tag('dt', $term);
				$output[] = $this->Html->tag('dd', $def, $def_opt);
			}
			$options = array();
		} else {
			foreach($data as $values) {
				$term = is_array($values[0])
					? $values[0][0]
					: $values[0];
				$term_opt = is_array($values[0]) 
					? $values[0][1]
					: array();
				
				$def = is_array($values[1])
					? $values[1][0]
					: $values[1];
				$def_opt = (is_array($values[1]) && count($values[1]) > 1)
					? $values[1][1]
					: array();
				
				$output[] = $this->Html->tag('dt', $term, $term_opt);
				$output[] = $this->Html->tag('dd', $def, $def_opt);
			}
		}
		
		isset($options['class'])
			? $options['class'] .= ' clearfix'
			: $options['class'] = 'clearfix';
		
		$output = $this->Html->tag('dl', implode($output), $options);
		
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