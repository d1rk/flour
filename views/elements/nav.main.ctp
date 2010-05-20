<?php

	$this->Nav->add('Main', array(
		'name' => __('Home', true),
		'url' => array('controller' => 'pages', 'action' => 'display', 'home'),
		'type' => 'Html',
	));

echo $this->Nav->show('Main', 'main');
?>