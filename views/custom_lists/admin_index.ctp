<?php
$this->Html->addCrumb( __('CustomLists', true), array('controller' => 'custom_lists', 'action' => 'index'));

$links = array();
$links[] = $this->Button->link(
	__('Add CustomList', true), 
	array('controller' => 'custom_lists', 'action' => 'add'),
	array('ico' => 'add')
);

echo $this->element('nav.sub', array('links' => $links));
echo $this->Html->div('box');
	echo $this->Html->tag('h2', __('CustomLists', true));
	echo $this->Html->para('', __('Please create a new custom list.', true));
echo $this->Html->tag('/div'); //div.box

echo $grid->open();
	echo $this->element('table', array(
		'data' => $this->data,
		'element' => 'custom_lists/row',
		'search' => true,
	));
echo $grid->close();

?>