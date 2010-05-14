<?php
#if(Configure::read('Env.installed'))
$user = Authsome::get();

if(!empty($user))
{
	$this->Nav->add('Main', array(
		'name' => __('Home', true),
		'url' => array('controller' => 'pages', 'action' => 'display', 'home'),
		'type' => 'Html',
	));
} else {

}

echo $this->Nav->show('Main', 'main');
?>