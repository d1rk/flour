<?php
/**
 * Activity Model
 * 
 * @package default
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com
 **/
class Activity extends AppModel
{
	var $tablePrefix = 'flour_';
	var $types = array(
		'object_created' => 'Created object \':name\' [:id]',
		'object_updated' => 'Updated object \':name\' [:id]',
		'object_deleted' => 'Deleted object \':name\' [:id]',
	);

	function write($type, $data)
	{
		if(empty($data['stage_id'])) $data['stage_id'] = null;
		if(empty($data['project_id'])) $data['project_id'] = null;

		$row = array(
			'type' => $type,
			'data' => serialize($data),
		);
		if($this->create($row) && $this->save())
		{
			//everything went fine
			return $this->read($type, $data);
		}
		return false;
	}

	function read($type, $data)
	{
		foreach($data as $key => $value)
		{
			if(is_array($value)) unset($data[$key]);
		}
		arsort($data);
		return String::insert($this->types[$type], $data);
	}

}
?>