<?php
$nav = array();
if(Configure::read('Env.installed')) $user = Authsome::get();

if(!empty($user))
{
	$link = array('controller' => 'pages', 'action' => 'display', 'home');
	$active = (Router::url($link) == $this->here) ? 'active' : '';
	$nav[] = $this->Html->link( __('Home', true), $link, array('class' => $active));

} else {

}

echo $this->Html->nestedList($nav);
?>