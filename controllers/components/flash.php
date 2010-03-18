<?php
/**
 * Flash Component
 * 
 * Wrapper class to "setFlash" setting the key according to message type
 * 
 * Example: error() generates <div id="errorMessage" class="message">
 * Benefit: allows better styling based on state
 * 
 * Built in redirect after flash:
 *   $this->Flash->error('go away', '/');
 *   $this->Flash->success( __('welcome!', true), array('action' => 'dashboard'));
 *   $this->Flash->success( __('welcome :user!', true), array('user' => 'd1rk'), array('action' => 'dashboard'));
 * 
 * @author Dirk Brünsicke
 */
App::import('Core', array('String', 'Set'));
class FlashComponent extends Object 
{
	public $components = array('Session', 'RequestHandler');
	
	public $filterAjax = true;
	
	public function initialize($controller)
	{
		$this->Controller = $controller;
	}
		
	public function msg($key = 'default', $message = '', $replace = array(), $redirect = false)
	{
		$message = (is_array($replace) && !empty($replace))
			? String::insert($message, Set::flatten($replace))
			: $message;

		// Halt and put JSON if request was AJAX. 
		if ($this->RequestHandler->isAjax() && $this->filterAjax) {
			$this->RequestHandler->respondAs('json');
			echo "// FLASH MESSAGE ".time()."\n";
			die('{"message":"'.$message.'", "type":"'.$key.'"}');
		}
		
		$this->Session->setFlash($message, 'flash_'.$key);	
		
		if ($redirect)
		{
			$this->Controller->redirect($redirect);
		}
	}
	
	public function error($message, $replace = false, $redirect = false) {
		$this->msg('error', $message, $replace, $redirect);
	}
	
	public function success($message, $replace = false, $redirect = false) {
		$this->msg('success', $message, $replace, $redirect);
	}
	
	public function info($message, $replace = false, $redirect = false) {
		$this->msg('info', $message, $replace, $redirect);
	}
}