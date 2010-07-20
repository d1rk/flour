<?php
/**
 * Login_Token Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class LoginToken extends FlourAppModel
{
	var $hasOne = array(
		'User'
	);
}
?>