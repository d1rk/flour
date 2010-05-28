<?php
$this->title = __('Pages', true);
$this->description = __('Please administer your pages here.', true);

$this->addCrumb(
	__('Pages', true),
	array('controller' => 'pages', 'action' => 'index')
);

$this->Nav->add('Primary', array(
	'name' => __('Add Page', true),
	'url' => array('controller' => 'pages', 'action' => 'add'),
	'type' => 'link',
	'ico' => 'add',
));


echo $this->element('content_start');

	echo $this->Grid->open();
		echo $this->element('table', array(
			'element' => 'pages/row',
		#	'search' => true,
		));
	echo $this->Grid->close();

echo $this->element('content_stop');

?>