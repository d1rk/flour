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
 * @subpackage	plugins.tags.controllers
 */

class TagsController extends TagsAppController {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Tags';

/**
 * Helpers
 *
 * @var array $name
 * @access public
 */
	public $helpers = array('Html', 'Form');

/**
 * Index
 *
 * @return void
 * @access public
 */
	public function index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}

/**
 * Index
 *
 * @param string
 * @return void
 * @access public
 */
	public function view($keyName = null) {
		try {
			$this->set('tag', $this->Tag->view($keyName));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
	}

/**
 * Admin Index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}

/**
 * Views a single tag
 *
 * @param string tag UUID
 * @return void
 * @access public
 */
	public function admin_view($keyName) {
		try {
			$this->set('tag', $this->Tag->view($keyName));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
	}

/**
 * Adds one or more tags
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->data)) {
			if ($this->Tag->add($this->data)) {
				$this->Session->setFlash(__d('tags', 'The Tags has been saved.', true));
				$this->redirect(array('action' => 'index'));
			}
		}
	}

/**
 * Edits a tag
 *
 * @param string tag UUID
 * @return void
 * @access public
 */
	public function admin_edit($tagId = null) {
		try {
			$result = $this->Tag->edit($tagId, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('tags', 'Tag saved.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data = $result;
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->data)) {
			$this->data = $this->Tag->data;
		}
	}

/**
 * Deletes a tag
 *
 * @param string tag UUID
 * @return void
 * @access public
 */
	public function admin_delete($id = null) {
		if ($this->Tag->delete($id)) {
			$this->Session->setFlash(__d('tags', 'Tag deleted.', true));
		} else {
			$this->Session->setFlash(__d('tags', 'Invalid Tag.', true));
		}
		$this->redirect(array('action' => 'index'));
	}
}
?>