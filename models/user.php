<?php
/**
 * User Model
 * 
 * You can use this model or copy it to your app.
 *
 * @package default
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class User extends AppModel
{
	var $tablePrefix = 'flour_';

	public $hasMany = array(
		'Flour.LoginToken'
	);
	
	var $validate = array(
		'name' => array(
			'isUnique' => array('rule' => 'isUnique'),
		),
		'email' => array(
			'isUnique' => array('rule' => 'isUnique'),
			'isEmail' => array('rule' => 'email'),
		),
	);
	
	public function authsomePersist($user, $duration)
	{
		$token = md5(uniqid(mt_rand(), true));
		$userId = $user['User']['id'];

		$this->LoginToken->create(array(
			'user_id' => $userId,
			'token' => $token,
			'duration' => $duration,
			'expires' => date('Y-m-d H:i:s', strtotime($duration)),
		));
		$this->LoginToken->save();

		return "${token}:${userId}";
	}
	
	public function authsomeLogin($type, $credentials = array())
	{
		switch ($type)
		{
			case 'guest':
				// You can return any non-null value here, if you don't
				// have a guest account, just return an empty array
				return array();
				
			case 'credentials':

				// This is the logic for validating the login
				$conditions = array(
					'User.name' => $credentials['name'],
					'User.password' => Authsome::hash($credentials['password']),
				);
				break;
				
			case 'cookie':
				list($token, $userId) = split(':', $credentials['token']);
				$duration = $credentials['duration'];

				$loginToken = $this->LoginToken->find('first', array(
					'conditions' => array(
					'user_id' => $userId,
					'token' => $token,
					'duration' => $duration,
					'used' => false,
					'expires <=' => date('Y-m-d H:i:s', strtotime($duration)),
				),
				'contain' => false
				));

				if (!$loginToken) {
					return false;
				}

				$loginToken['LoginToken']['used'] = true;
				$this->LoginToken->save($loginToken);

				$conditions = array(
					'User.id' => $loginToken['LoginToken']['user_id']
				);
				break;
			default:
				return null;
		}
		$user = $this->find('first', compact('conditions'));
		if (!$user) {
			return false;
		}
		$user['User']['loginType'] = $type;
		return $user;
	}
	
}
?>