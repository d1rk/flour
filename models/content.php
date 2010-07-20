<?php
/**
 * Content Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Content extends FlourAppModel
{
	var $actsAs = array(
		'Flour.Flexible',
	);
	
	var $hasMany = array(
		'Flour.ContentField',
	);

	/**
	 * @var array controls validation
	 * @access private
	 */
	var $validate = array(
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'isUnique' => array('rule' => 'isUnique'),
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

	function get($slug, $field = null, $options = array())
	{
		$conditions = array();
		$contain = array();
		$order = 'created DESC';

		$conditions['Content.status >='] = 1;
		$conditions['Content.slug'] = $slug;

		$data = $this->find('first', compact('conditions', 'contain', 'order'));
		return $data;
	}

	
}
?>