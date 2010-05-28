<?php
$this->title = __('Pages', true);
$this->description = __('Please add your page.', true);

$this->addCrumb(
	__('Pages', true),
	array('controller' => 'pages', 'action' => 'index')
);

$this->addCrumb(
	__('add', true),
	array('controller' => 'pages', 'action' => 'add')
);

$this->Nav->add('Primary', array(
	'name' => __('cancel', true),
	'url' => array('controller' => 'pages', 'action' => 'index'),
	'type' => 'link',
	'ico' => 'cross',
));

$this->Nav->add('Primary', array(
	'name' => __('save', true),
	'type' => 'button',
	'ico' => 'disk',
	'class' => 'positive',
));

echo $this->Form->create('Page');
echo $this->element('content_start');

	echo $this->Grid->open();
		echo $this->element('pages/form');
	echo $this->Grid->close();

echo $this->element('content_stop');
echo $this->Form->end();

?>