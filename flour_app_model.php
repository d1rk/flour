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
	 * @var array
	 * @access private
	 */
	function beforeSave()
	{
		$this->_addUserdata();

	}

	/**
	 * flour afterSave
	 *
	 * @var array
	 * @access private
	 */
	function afterSave()
	{
		

	}

	/**
	 * flour find
	 *
	 * @var array
	 * @access private
	 */
	function find($type, $options = array())
	{
		switch ($type) {
			case 'active':
				if (!isset($options['conditions']) ) {
					$options['conditions'] = array();
				} 

				if (!isset($options['contain']) ) {
					$options['contain'] = array();
				} 

				if (!isset($options['order']) ) {
					$options['order'] = array('Lottery.ending ASC');
				} 

				$options['conditions'] = array_merge(
					$options['conditions'],
					array(
						$this->alias.'.status' => 1,
						$this->alias.'.valid_from <=' => date('Y-m-d H:i:s'),
						$this->alias.'.valid_to >=' => date('Y-m-d H:i:s'),
					)
				);
				
				$type = (  isset($options['conditions']['Lottery.id']) 
						|| isset($options['conditions']['Lottery.slug']))
					? 'first'
					: 'all';

			break;
		}
		return parent::find($type, $options);
	}

	function beforeFind($queryData) {
		// debug($queryData);
		return true;
	}

	function _addUserData(&$this->data)
	{
//		$this->data
	}

}
?>