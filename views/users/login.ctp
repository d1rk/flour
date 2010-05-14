<?php

$this->Nav->add('Primary', array(
	'name' => __('login', true),
	'type' => 'button',
	'ico' => 'disk',
	'class' => 'positive',
));

$this->title = __('Login', true);

$this->description = __('Please enter your login-name and your password to login.', true);

echo $this->Form->create('User', array('action' => $this->action));
echo $this->element('content_start');

	echo $this->Grid->open();
		echo $this->Html->div('panel');

			echo $this->element('users/form');

		echo $this->Html->tag('/div'); //div.panel
	echo $this->Grid->close();

echo $this->element('content_stop');
echo $form->end();

?>