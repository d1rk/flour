<?php
$title = (isset($title))
	? $title
	: $this->title;

$this->name = $title;

echo $this->Nav->show('Primary', 'primary');
echo $this->Html->div('box');
	echo $this->Html->tag('h2', $this->title);
	echo $this->Html->para('description', $this->description);
echo $this->Html->tag('/div'); //div.box
?>