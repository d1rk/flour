<?php
/**
 * Login_Token Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class LoginToken extends AppModel
{
	var $tablePrefix = 'flour_';

	var $hasOne = array(
		'User'
	);
}
?>