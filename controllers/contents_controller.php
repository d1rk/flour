<?php
/**
 * ContentsController
 * 
 * Content is an object with unlimited amount of data.
 * It has a state and several meta-data that controls how and where
 * it is displayed.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class CustomListsController extends AppController
{
	var $paginate = array(
		'CustomList' => array(
			'limit' => 100,
			'recursive' => 1,
		),
	);


/**
 * Index action.
 *
 * @access public
 */
	function admin_index()
	{
		$conditions = array();
		//$conditions = $this->_buildSearchConditions('CustomList', array(
		//	'CustomList.name',
		//));
		$this->data = $this->paginate('CustomList', $conditions);
	}
	


/**
 * View action
 *
 * @access public
 * @param integer $id ID of record
 */	
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid CustomList.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('data', $this->CustomList->read(null, $id));
	}

/**
 * Called before the controller action.
 *
 * @access public
 */
	function beforeFilter() {
	}

/**
 * Called after the controller action is run, but before the view is rendered.
 *
 * @access public
 */
	function beforeRender() {
	}

/**
 * Called after the controller action is run and rendered.
 *
 * @access public
 */
	function afterFilter() {
	}
}
?>