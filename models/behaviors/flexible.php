<?php
/**
 * Model behavior Flexible.
 *
 * Allows a Model to save an unlimited number of virtual fields. It even take care of values that are of type array.
 *
 * PHP version 5
 *
 * Copyright 2009, Dirk Bruensicke. (http://bruensicke.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author	   Dirk Bruensicke <dirk@bruensicke.com>
 * @copyright  2009 Dirk Bruensicke
 * @license	   http://www.opensource.org/licenses/mit-license.php	The MIT License
 */
class FlexibleBehavior extends ModelBehavior
{

/**
 * Contains configuration settings for use with individual model objects.  This
 * is used because if multiple models use this Behavior, each will use the same
 * object instance.	 Individual model settings should be stored as an
 * associative array, keyed off of the model name.
 *
 * @var array
 * @access public
 * @see Model::$alias
 */
	var $settings = array();

/**
 * Per default a Model called <Model>Field is used for storing the virtual fields. To overwrite pass an array like:
 * array('with' => OtherModel) as the actsAs parameter.
 *
 * @param object $Model
 * @param array $settings
 * @return array
 * @access public
 */
	function setup(&$Model, $settings = array())
	{
		$base = array('schema' => $Model->schema());
		if (isset($settings['with'])) //connect with given model
		{
			$conventions = array('foreignKey', $Model->hasMany[$settings['with']]['foreignKey']);
			return $this->settings[$Model->alias] = am($base, $conventions, $settings);

		} else { //create the needed Field-Model dynamically

			$assoc = $Model->alias.'Field';
			if (!array_key_exists($assoc, $Model->hasMany))
			{
				$Model->hasMany[$assoc] = array('with' => $assoc, 'foreignKey' => low($Model->alias).'_id', 'dependent' => true, 'conditions' => array());
				$Model->$assoc = ClassRegistry::init(array('class' => $assoc, 'type' => $assoc, 'ds' => $Model->useDbConfig));
			}
		}

		foreach ($Model->hasMany as $assoc => $option)
		{
			if (strpos($assoc, 'Field') !== false)
			{
				$conventions = array('with' => $assoc, 'foreignKey' => $Model->hasMany[$assoc]['foreignKey'], 'dependent' => true, 'conditions' => array());
				return $this->settings[$Model->alias] = am($base, $conventions, !empty($settings) ? $settings : array());
			}
		}
	}

/**
 * Merges the virtual fields into the main Model record.
 *
 * @param object $Model
 * @param array $results
 * @param boolean $primary
 * @return array
 * @access public
 */
	function afterFind(&$Model, $results, $primary)
	{
		extract($this->settings[$Model->alias]);
		foreach ($results as $i => $item)
		{
			if (!isset($item[$with]))
			{
				continue;
			}
			foreach ($item[$with] as $field)
			{
				$results[$i][$Model->alias][$field['name']] = $field['val'];
			}
		}
		$results = $this->unserialize_items($results); //will convert JSON Strings back to arrays
		return $results;
	}

/**
 * Flattens all $keys
 *
 * @param object $Model
 * @access public
 */
	function beforeValidate(&$Model)
	{
		if(!empty($Model->data))
		{
			$Model->data[$Model->name] = Set::flatten($Model->data[$Model->name]);
		}
		return true;
	}

/**
 * Converts all field-values that are arrays into JSON-objects.
 *
 * @param object $Model
 * @access public
 */
	function beforeSave(&$Model)
	{
		$fields = $Model->data[$Model->alias];
		foreach ($fields as $key => $val)
		{
			if(is_array($val))
			{
				$val = json_encode($val);
			}
			$Model->data[$Model->alias][$key] = $val;
		}
	}

/**
 * Saves all fields that do not belong to the current Model into 'with' helper model.
 *
 * @todo should be refactored to make a find('all') and it should take care of arrays for itself
 * @param object $Model
 * @access public
 */
	function afterSave(&$Model)
	{
		extract($this->settings[$Model->alias]);
		$fields = array_diff_key($Model->data[$Model->alias], $schema);
		$id = $Model->id;
		foreach ($fields as $key => $val)
		{
			$field = $Model->{$with}->find('first', array(
				'fields' => array($with.'.id'),
				'conditions' => array($with.'.'.$foreignKey => $id, $with.'.name' => $key),
				'recursive' => -1,
			));
			$Model->{$with}->create(false);
			if ($field) {
				$Model->{$with}->set('id', $field[$with]['id']);
			} else {
				$Model->{$with}->set(array($foreignKey => $id, 'name' => $key));
			}
			$Model->{$with}->set('val', $val);
			$Model->{$with}->save();
		}
	}

/**
 * Checks if string is serialized array/object
 * json_decode returns NULL in some environments, if $str is not encoded
 *
 * @param string string to check
 * @access public
 * @return boolean
 */
	function isSerialized($str)
	{
		// json_decode returns NULL in some environments, if $str is not encoded
		return (// check json notation
				preg_match('/^[\{].*[\}]$|^[\[].*[\]]$/',$str)
				&& 
				// handle NULL - values
				($str === "null"
				|| @json_decode($str) !== NULL)
				&&
				// handle FALSE - values
				( $str == json_decode(false)
				|| @json_decode($str) !== false )
		);
	}

/**
 * Unserializes the fields of an array (if the value itself was serialized)
 *
 * @param array $arr
 * @return array
 * @access public
 */
	function unserialize_items($arr)
	{
		foreach($arr as $key => $val)
		{
			if(is_array($val))
			{
				$val = $this->unserialize_items($val);
			} elseif($this->isSerialized($val))
			{
				$val = json_decode($val, true); //converts to array
			}
			$arr[$key] = $val;
		}
		return $arr;
	}

}

?>