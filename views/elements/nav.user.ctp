<?php
$nav = array();
if(Configure::read('Env.installed')) $user = Authsome::get();

if(!empty($user))
{
	$nav[] = $this->Html->tag('span', String::insert( __('hello :name', true), $user['User']));

	$link = array('controller' => 'users', 'action' => 'settings');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('settings', true), $link, array('class' => $active));

	$link = array('controller' => 'users', 'action' => 'logout');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('logout', true), $link, array('class' => $active));

} else {

	$link = array('controller' => 'users', 'action' => 'login');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('login', true), $link, array('class' => $active));

	$link = array('controller' => 'users', 'action' => 'register');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('register', true), $link, array('class' => $active));
}


echo $this->Html->nestedList($nav);
?>