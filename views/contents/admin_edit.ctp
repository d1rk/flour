<?php
$this->title = __('Contents', true);
$this->description = __('Edit Content details.', true);

$this->addCrumb(
	__('Contents', true),
	array('controller' => 'contents', 'action' => 'index')
);

$this->addCrumb(
	$this->data['Content']['name'],
	array('controller' => 'contents', 'action' => 'view', $this->data['Content']['id'])
);

$this->addCrumb(
	__('edit', true),
	array('controller' => 'contents', 'action' => 'edit', $this->data['Content']['id'])
);

$this->Nav->add('Primary', array(
	'name' => __('cancel', true),
	'url' => array('controller' => 'contents', 'action' => 'index'),
	'type' => 'link',
	'ico' => 'cross',
	'confirm' => __('Are you sure you want to cancel?', true),
));

$this->Nav->add('Primary', array(
	'name' => __('save', true),
	'type' => 'button',
	'ico' => 'disk',
	'class' => 'positive',
));

echo $this->Form->create('Content', array('action' => $this->action));
echo $this->Form->hidden('Content.id');
echo $this->element('content_start');
	echo $this->Grid->open();

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			$type = (isset($this->params['named']['type']))
				? $this->params['named']['type']
				: 'form_basic';

			echo $this->element('box', array(
				'caption' => __('Enter Content Details.', true),
				'content' => $this->element('contents/'.$type), //TODO: make switchable
				'class' => 'panel',
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('box', array(
				'caption' => __('Control Content', true),
				'content' => $this->element('contents/form'),
				'class' => 'panel',
			));

		echo $this->Html->tag('/div'); //div.span-10 last

	echo $this->Grid->close();
echo $this->element('content_stop');
echo $form->end();

?>