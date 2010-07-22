<?php
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.content', array(
		'type' => 'textarea',
		'default' => '<p>put your html here</p>',
	));


} else {

	if(isset($data['content'])) echo $data['content'];
	// echo String::insert($$template, Set::flatten($data));
}

?>