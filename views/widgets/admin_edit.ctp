<?php
$this->title = __('Widgets', true);
$this->description = __('Edit Widget details.', true);

$this->addCrumb(
	__('Widgets', true),
	array('controller' => 'widgets', 'action' => 'index')
);

$this->addCrumb(
	$this->data['Widget']['name'],
	array('controller' => 'widgets', 'action' => 'view', $this->data['Widget']['id'])
);

$this->addCrumb(
	__('edit', true),
	array('controller' => 'widgets', 'action' => 'edit', $this->data['Widget']['id'])
);

$this->Nav->add('Primary', array(
	'name' => __('cancel', true),
	'url' => array('controller' => 'widgets', 'action' => 'index'),
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

$type = $this->data['Widget']['type'];

echo $this->Form->create('Widget', array('action' => $this->action));
echo $this->Form->hidden('Widget.id');
echo $this->element('content_start');
	echo $this->Grid->open();

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('box', array(
				'caption' => __('Enter Widget Details.', true),
				'content' => $this->element('widget', array('type' => $type, 'template' => 'admin')), //TODO: make switchable
				'class' => 'panel',
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('box', array(
				'caption' => __('Control Widget', true),
				'content' => $this->element('widgets/form'),
				'class' => 'panel',
			));

		echo $this->Html->tag('/div'); //div.span-10 last

		echo $this->Html->div('span-24');

			echo $this->element('box', array(
				'caption' => __('Preview Widget', true),
				'content' => $this->element('widget', array(
					'type' => $type,
					'template' => 'default',
					'widget_data' => $this->data['Widget']['data'],
				)),
				'class' => 'panel',
			));
		
		echo $this->Html->tag('/div'); //div.span-24

	echo $this->Grid->close();
echo $this->element('content_stop');
echo $form->end();

?>