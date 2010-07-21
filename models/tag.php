<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *                        1785 E. Sahara Avenue, Suite 490-423
 *                        Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link      http://github.com/CakeDC/Tags
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * @package		plugins.tags
 * @subpackage	plugins.tags.models
 */

class Tag extends FlourAppModel {

/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Tag';

/**
 * hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Tagged' => array(
			'className' => 'Flour.Tagged',
			'foreignKey' => 'tag_id'));

/**
 * HABTM associations
 *
 * @var array $hasAndBelongsToMany
 * @access public
 */
	public $hasAndBelongsToMany = array();

/**
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'name' => array('rule' => 'notEmpty'),
		'keyname' => array('rule' => 'notEmpty'));

/**
 * Returns the data for a single tag
 *
 * @param string keyname
 * @return array
 * @access public
 */
	public function view($keyName = null) {
		$result = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.keyname' => $keyName)));

		if (empty($result)) {
			throw new Exception(__d('tags', 'Invalid Tag.', true));
		}
		return $result;
	}


/**
 * Pre-populates the tag table with entered tags
 *
 * @param array post data, should be Contoller->data
 * @return boolean
 * @access public
 */
	public function add($postData = null) {
		if (isset($postData[$this->alias]['tags'])) {
			$this->Behaviors->attach('Flour.Taggable', array(
				'resetBinding' => true,
				'automaticTagging' => false));
			$this->Tag = $this;
			$result = $this->saveTags($postData[$this->alias]['tags'], false, false);
			unset($this->Tag);
			$this->Behaviors->detach('Flour.Taggable');
			return $result;
		}
	}

/**
 * Edits an existing tag, allows only to modify upper/lowercased characters
 *
 * @param string tag uuid
 * @param array controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 * @access public
 */
	public function edit($tagId = null, $postData = null) {
		$tag = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'Tag.id' => $tagId)));

		$this->set($tag);
		if (empty($tag)) {
			throw new Exception(__d('tags', 'Invalid Tag.', true));
		}

		if (!empty($postData[$this->alias]['name'])) {
			if (strcasecmp($tag['Tag']['name'], $postData[$this->alias]['name']) !== 0) {
				return false;
			}
			$this->set($postData);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $postData;
			}
		}
	}

}
?>