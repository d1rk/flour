<?php
/**
 * Content Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Content extends AppModel
{
	var $tablePrefix = 'flour_';

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

}
?>