<?php
//retrieve the widget to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: 'example';

echo $this->Form->input('Widget.type', array(
	'type' => 'select',
	'options' => Configure::read('App.Widget.types'),
	'default' => $type,
	'id' => 'WidgetTypeSelect',
));

echo $this->Form->input('Widget.status');

echo $this->Form->input('Widget.name');
echo $this->Form->input('Widget.slug');

echo $this->Form->input('Widget.description');
?>