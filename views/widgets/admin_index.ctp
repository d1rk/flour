<?php
$this->title = __('Widgets', true);
$this->description = __('Show all Widgets', true);

$this->addCrumb(
	__('Widgets', true),
	array('controller' => 'widgets', 'action' => 'index')
);

$this->Nav->add('Primary', array(
	'name' => __('Add Widget', true),
	'url' => array('controller' => 'widgets', 'action' => 'add'),
	'type' => 'link',
	'ico' => 'add',
));

echo $this->element('content_start');
	echo $grid->open();

		$filters = $actions = array();

		$actions[__('edit', true)] = array('controller' => 'lotteries', 'action' => 'edit', ':id');
		$actions[__('view', true)] = array('controller' => 'lotteries', 'action' => 'view', ':id');
		$actions[__('delete', true)] = array('controller' => 'lotteries', 'action' => 'delete', ':id');

		echo $this->element('table', array(
			'element' => 'widgets/row',
			'search' => 'Widget',
			'actions' => $actions,
			'current_searchterms' => (isset($search)) ? $search : '',
		));
	echo $grid->close();
echo $this->element('content_stop');
?>