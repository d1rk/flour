<?php
/**
 * Login_Token Model
 * 
 * You can use this one.
 *
 * @package default
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