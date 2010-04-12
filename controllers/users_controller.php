<?php
/**
 * UsersController
 * 
 * [Short Description]
 *
 * @package default
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/

class UsersController extends AppController {
/**
 * The name of this controller. Controller names are plural, named after the model they manipulate.
 *
 * @var string
 * @access public
 */
	var $name = 'Users';

/**
 * An array containing the names of helpers this controller uses. The array elements should
 * not contain the "Helper" part of the classname.
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @access protected
 */
	var $helpers = array('Html', 'Form');

/**
 * Array containing the names of components this controller uses. Component names
 * should not contain the "Component" portion of the classname.
 *
 * @var array
 * @access public
 */
	var $components = array();


/**
 * Index action.
 *
 * @access public
 */
	function index() {

	}

/**
 * View action
 *
 * @access public
 * @param integer $id ID of record
 */	
	function view($id = null)
	{
		if (!$id)
		{
			$this->Session->setFlash( __('Invalid User.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('data', $this->User->read(null, $id));
	}

/**
 * register action
 *
 * @access public
 */	
	function register()
	{
		if (!empty($this->data))
		{
			$this->User->create();
			$this->data['User']['password'] = Authsome::hash($this->data['User']['password']);
			if($this->User->save($this->data))
			{
				$this->Session->setFlash('User was saved');
				$this->redirect(array('controller' => 'users', 'action'=>'login'));
			}
		}
	}

/**
 * logout action
 *
 * @access public
 */	
	function logout()
	{
		Authsome::logout();
		$this->redirect('/');
	}

/**
 * enables login for a User
 *
 * @access public
 */	
	public function login()
	{
		if (empty($this->data))
		{
			return;
		}
		$user = Authsome::login($this->data['User']);

		if (!$user)
		{
			$this->Session->setFlash('Unknown user or wrong password');
			return;
		}
		
		$remember = (!empty($this->data['User']['remember']));
		if ($remember) {
			Authsome::persist('2 weeks');
		}

		$this->Session->setFlash( __('Logged in successfully.', true));
		$this->redirect('/');
	}
}
?>