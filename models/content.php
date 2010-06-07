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

}
?>