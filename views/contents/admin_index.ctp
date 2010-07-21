<?php
$this->title = __('Contents', true);
$this->description = __('Show all Contents', true);

$this->addCrumb(
	__('Contents', true),
	array('controller' => 'contents', 'action' => 'index')
);

$this->Nav->add('Primary', array(
	'name' => __('Add Content', true),
	'url' => array('controller' => 'contents', 'action' => 'add'),
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
			'element' => 'contents/row',
			'search' => 'Content',
			'actions' => $actions,
			'current_searchterms' => (isset($search)) ? $search : '',
		));
	echo $grid->close();
echo $this->element('content_stop');
?>