<?php
/**
 * Activity Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Activity extends FlourAppModel
{
	var $actsAs = 'Flour.Taggable';

	var $tablePrefix = 'flour_';
	var $types = array(
		'user_loggedin' => 'user \':User.name\' logged in via \':User.loginType\'.',
		'user_loggedout' => 'user \':User.name\' logged out.',
		'object_created' => 'Created object \':name\' [:id]',
		'object_updated' => 'Updated object \':name\' [:id]',
		'object_deleted' => 'Deleted object \':name\' [:id]',
	);

	function __construct()
	{
		if($types = Configure::read('Flour.Activities.types'))
		{
			$this->types = array_merge($types, $this->types);
		}
		return parent::__construct();
	}

	function write($type, $data)
	{
		$row = array(
			'type' => $type,
			'data' => $data,
		);
		if($this->create($row) && $this->save())
		{
			//everything went fine
			return $this->parse($type, $data);
		}
		return false;
	}

	function beforeSave($options = null)
	{
		// if no user was written to config so far, we won't get the user here
		// if you want the Activity log to contain the user_id, call
		// Authsome::get() before writing to the activity log. Authsome then has the
		// user context stored to config.
		$config_user = Configure::read(Authsome::instance()->settings['configureKey']);
		if(!empty($config_user))
		{
			$user_id = Authsome::get('User.id');
			$this->data[$this->alias]['user_id'] = $user_id;
		}
		
		$this->data[$this->alias]['message'] = $this->parse($this->data[$this->alias]['type'], $this->data[$this->alias]['data']);
		$this->data[$this->alias]['data'] = json_encode($this->data[$this->alias]['data']);
		return parent::beforeSave($options);
	}

	//overwritten! Activities can not be deleted.
	function delete()
	{
		return false;
	}

	//parse message
	function parse($type, $data)
	{
		if(!array_key_exists($type, $this->types)) {
			return false;
		}
		arsort($data);
		return String::insert($this->types[$type], Set::flatten($data));
	}

	//get latest activities
	function get($limit = 25, $options = array())
	{
		$defaults = array(
			'order' => 'Activity.created DESC',
		);
		$options = array_merge($options, $defaults);
		$options['limit'] = $limit;
		return $this->find('all', $options);
	}
}
?>