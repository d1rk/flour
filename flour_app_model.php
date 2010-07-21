<?php
/**
 * Flour App Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class FlourAppModel extends AppModel
{
	var $tablePrefix = 'flour_';

	/**
	 * flour beforeSave
	 *
	 * @access public
	 */
	function beforeSave()
	{		
		$this->_addUserdata();
		return true;
	}
	
	/**
	 * flour soft delete
	 *
	 * @access public
	 */
	function delete($id = null, $cascade = true) {
		if (!empty($id)) {
			$this->id = $id;
		}
		$id = $this->id;

		if(!$this->hasField('deleted')) {
			return parent::delete($id, $cascade);
		}
		
		$user = $this->_getUser();
		if($user) {
			$this->data[$this->alias]['deleted_by'] = $user;
		}
		$this->data[$this->alias]['deleted'] = date('Y-m-d H:i:s');
		
		// TODO: handle cascade delete / soft delete
		
		return $this->save($this->data);
	}
	
	/**
	 * flour undelete
	 *
	 * @access public
	 */
	function undelete($id) {
		$this->id = $id;
		if(!$this->hasField('deleted') || !$this->exists()){
			return false;
		}
		$this->data = $this->read(null, $id);
		$this->_addUserData();
		$this->data[$this->alias]['deleted'] = null;
		$this->data[$this->alias]['deleted_by'] = null;
		
		return $this->save($this->data);
	}
	
	
	/**
	 * flour find
	 *
	 * @var array
	 * @access private
	 */
	function find($type, $options = array())
	{
		if (!isset($options['conditions']) ) {
			$options['conditions'] = array();
		} 
		
		if (!isset($options['contain']) ) {
			// This kills all non-explicit contains like hasMany etc
			// $options['contain'] = array();
		} 
		
		if (!isset($options['order']) ) {
			$options['order'] = array();
		}
		
		if($this->hasField('deleted') && ! isset($options['conditions'][$this->alias.'.'.$this->primaryKey])) {
			$options['conditions'][$this->alias.'.deleted'] = null;
		}
		
		switch ($type) {
			case 'active':
				$this->_setActive($options, $type);
				break;
			case 'current':
				$this->_setCurrent($options, $type);
				break;
			case 'editions':
				$this->_setEditions($options, $type);
				break;
			case 'deleted':
				$this->_setDeleted($options, $type);
				break;
		}
		return parent::find($type, $options);
	}
	
	function _setActive(&$options, &$type)
	{
		$this->_setValid($options, $type);
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.status' => 1,
			)
		);
		$type = (isset($options['conditions'][$this->alias.'.'.$this->primaryKey]))
			? 'first'
			: 'all';
	}
	
	function _setCurrent(&$options, &$type) {
		$this->_setValid($options, $type);
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.status' => 1,
			)
		);
		$options['order'] = array_merge(
			$options['order'],
			array(
				$this->alias.'.valid_from' => 'DESC',
			)
		);
		$type = 'first';
	}
	
	function _setEditions(&$options, &$type) {
		$slug = null;
		
		// find all editions with same slug by reference id:
		if(isset($options['conditions'][$this->alias.'.'.$this->primaryKey])) {
			$slug = $this->field('slug', $options['conditions']);
		}
		
		// find all editions with given slug
		if(isset($options['conditions'][$this->alias.'.slug'])) {
			$slug = $options['conditions'][$this->alias.'.slug'];
		}

		unset($options['conditions'][$this->alias.'.'.$this->primaryKey]);
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.slug' => $slug,
			)
		);
		
		$type = 'all';
	}
	
	function _setDeleted(&$options, &$type) {
		unset($options['conditions'][$this->alias.'.deleted']);
		
		$options['conditions'] = array_merge(
			$options['conditions'],
			array( 'NOT' => array(
					$this->alias.'.deleted' => NULL
				),
			)
		);
		$type = 'all';
	}
	
	function _setValid(&$options, &$type) {
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				'AND' => array(
					array(
						'OR' => array(
							$this->alias.'.valid_from <=' => date('Y-m-d H:i:s'),
							$this->alias.'.valid_from' => null,
						)
					),
					array(
						'OR' => array(
							$this->alias.'.valid_to >=' => date('Y-m-d H:i:s'),
							$this->alias.'.valid_to' => null,
						),
					),
				),
			)
		);
	}

	function _addUserData()
	{
		$user = $this->_getUser();
		if(!$user) return false;
		
		if(!isset($this->data[$this->alias][$this->primaryKey])) {
			$this->data[$this->alias]['created_by'] = $user;
		}
		
		$this->data[$this->alias]['modified_by'] = $user;
	}

	function _getUser()
	{
		$user = Configure::read('App.User.id');
		if(!empty($user)) return $user;
		
		$user = Configure::read('Auth.User.id');
		if(!empty($user)) return $user;
		
		return false;
	}
	
}
?>