<?php
$nav = array();
$user = Authsome::get();

if(!empty($user))
{
	$link = array('controller' => 'clients', 'action' => 'index');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('Clients', true), $link, array('class' => $active));

	$link = array('controller' => 'reports', 'action' => 'index');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('Reports', true), $link, array('class' => $active));
} else {

}

echo $this->Html->nestedList($nav);
?>