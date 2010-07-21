<?php
//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: 'example';

echo $this->Form->input('Content.type', array(
	'type' => 'select',
	'options' => Configure::read('App.Content.types'),
	'default' => $type,
));

echo $this->Form->input('Content.status');

echo $this->Form->input('Content.name');
echo $this->Form->input('Content.slug');

echo $this->Form->input('Content.description');
?>