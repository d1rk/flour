<?php
$links = array();
$links[] = $this->Button->button( __('login', true), array('ico' => 'disk', 'class' => 'positive'));


echo $form->create('User', array('action' => $this->action));
	echo $this->element('nav.sub', array('links' => $links));
	echo $this->Html->div('box');
		echo $this->Html->tag('h2', __('Login', true));
		echo $this->Html->para('', __('Please enter your login-name and your password to login.', true));
	echo $this->Html->tag('/div'); //div.box

	echo $this->Grid->open();
		echo $this->Html->div('panel');

			echo $this->element('users/form');

		echo $this->Html->tag('/div'); //div.panel
	echo $this->Grid->close();

echo $form->end();

?>