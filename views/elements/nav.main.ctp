<?php

	$this->Nav->add('Main', array(
		'name' => __('Home', true),
		'url' => array('controller' => 'pages', 'action' => 'display', 'home', 'admin' => false),
		'type' => 'Html',
	));

echo $this->Nav->show('Main', 'main');
?>