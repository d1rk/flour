<?php
/**
 * DocsController
 * 
 * Shows Documentation of Flour.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class DocsController extends AppController
{

	var $uses = null;

/**
 * Index action.
 *
 * @access public
 */
	function index()
	{

	}
	
/**
 * Shows Examples on how to use different parts of flour.
 *
 * @access public
 */
	function examples()
	{

	}
	
/**
 * Lists API Documentation of flour.
 *
 * @access public
 */
	function api()
	{

	}
	
	function beforeFilter()
	{
		$this->layout = 'docs';
	}

}
?>