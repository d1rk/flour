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

$this->Nav->add('Primary', array(
	'name' => __('edit', true),
	'url' => array('controller' => 'contents', 'action' => 'edit', $this->data['Content']['id']),
	'type' => 'link',
	'ico' => 'page_white_edit',
));

echo $this->Form->create('Content', array('action' => $this->action));
echo $this->Form->hidden('Content.id');
echo $this->element('content_start');
	echo $this->Grid->open();

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('box', array(
				'caption' => __('Enter Content Details.', true),
				'content' => $this->element('contents/form_basic'),
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