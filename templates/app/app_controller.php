<?php
/**
 * AppController
 *
 * @package default
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com
 **/
class AppController extends Controller
{

/**
 * components used by all controllers
 *
 * @var array a list of names as an array.
 */
	public $components = array(
		'Session',
		'Cookie',
		'RequestHandler',
		'Flour.Authsome',
		'Flour.Layout',
		'Flour.Flash',
	);

/**
 * helpers used by all controllers
 *
 * @var array a list of names as an array.
 * @access protected
 */
	var $helpers = array(
		'Session',
		'Html',
		'Form',
		'Text',
		'Time',
		'Flour.Grid',
		'Flour.Button',
	);

}
?>