<?php
/**
 * Page Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Page extends AppModel
{
	var $tablePrefix = 'flour_';
	var $useTable = 'nodes';

	var $actsAs = array(
		'Tree',
		'Flour.Flexible' => array('with' => 'NodeField', 'foreignKey' => 'node_id'),
	);
	
	var $hasMany = array(
		'NodeField' => array(
			'className' => 'Flour.NodeField',
			'foreignKey' => 'node_id',
			)
	);

	/**
	 * controls validations on ADD
	 *
	 * @var array
	 * @access private
	 */
	var $validate = array(
		'title' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

/*
	//we need to re-cache all sites, that this page belongs to
	function afterSave()
	{
		$parents = $this->getPath($this->id);
		$host_ids = Set::extract('/Page[type=site]/host_id', $parents); //get ids of all hosts, that this page belongs to
		$hashes = $this->Site->Host->find('list', array('fields' => array('Host.hash'), 'conditions' => array('Host.id' => $host_ids)));
		foreach($hashes as $hash)
		{
			$this->Site->_cache($hash); //rebuild site-cache
		}
	}
*/
}
?>