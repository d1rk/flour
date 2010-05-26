<?php
$nav = array();
$user = Authsome::get();

if(!empty($user))
{
	$nav[] = $this->Html->tag('span', String::insert( __('hello :name', true), $user['User']));

	$nav[] = $this->Html->link( __('home', true), '/');

	$link = array('plugin' => 'flour', 'controller' => 'users', 'action' => 'settings', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('settings', true), $link, array('class' => $active));

	$link = array('plugin' => 'flour', 'controller' => 'users', 'action' => 'logout', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('logout', true), $link, array('class' => $active));

} else {

	$link = '/';
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('home', true), $link, array('class' => $active));

	$link = array('plugin' => 'flour', 'controller' => 'users', 'action' => 'login', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('login', true), $link, array('class' => $active));

	$link = array('plugin' => 'flour', 'controller' => 'users', 'action' => 'register', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('register', true), $link, array('class' => $active));
}

echo $this->Html->nestedList($nav);
?>