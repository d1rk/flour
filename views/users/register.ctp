<?php

$this->Nav->add('Primary', array(
	'name' => __('cancel', true),
	'url' => '/',
	'type' => 'link',
	'ico' => 'cross',
));

$this->Nav->add('Primary', array(
	'name' =>  __('save', true),
	'type' => 'link',
	'ico' => 'disk',
	'class' => 'positive',
));

$this->title = __('Users', true);

$this->description = __('Please enter information about your person below.', true);

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