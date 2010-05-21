<?php
/**
 * AppController
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class AppController extends Controller
{

/**
 * components used by all controllers
 *
 * @var array a list of names as an array.
 */
	public $components = array(
		'Flour.Authsome',
		'Flour.Layout',
	);

}
?>