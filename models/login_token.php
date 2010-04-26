<?php
/**
 * Login_Token Model
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