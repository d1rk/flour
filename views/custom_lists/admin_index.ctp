<?php
$this->Html->addCrumb( __('CustomLists', true), array('controller' => 'custom_lists', 'action' => 'index'));

$this->Nav->add('Primary', array(
	'name' => __('Add CustomList', true),
	'url' => array('controller' => 'custom_lists', 'action' => 'add'),
	'type' => 'link',
	'ico' => 'add',
));

$this->title = __('CustomLists', true);

$this->description = __('Please create a new custom list.', true);

echo $this->element('content_start');

	echo $grid->open();
		echo $this->element('table', array(
			'element' => 'custom_lists/row',
			'search' => true,
		));
	echo $grid->close();

echo $this->element('content_stop');

?>