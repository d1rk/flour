<?php
// echo $this->Form->input('Content.type', array(
// 	'type' => 'select',
// 	'options' => Configure::read('App.Content.types'),
// ));


echo $this->Form->input('Content.type', array(
	'type' => 'text',
));


echo $this->Form->input('Content.status');

echo $this->Form->input('Content.name');
echo $this->Form->input('Content.slug');

echo $this->Form->input('Content.description');
?>