<?php
$data = (isset($data))
	? $data
	: $this->data;

if(is_array($data)) extract($data);

$title = (isset($title))
	? $title
	: null;

$body = (isset($body))
	? $body
	: null;

if(!empty($title)) echo $this->Html->tag('h3', $title);
if(!empty($body)) echo $this->Html->div('', $body);

?>