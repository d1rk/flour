<?php
$this->title = (isset($title))
	? $title
	: $this->title;

$this->description = (isset($description))
	? $description
	: $this->description;

$this->name = $this->title;

echo $this->Nav->show('Primary', 'primary');
echo $this->Html->div('box');
	if(!empty($this->title))
	{
		echo $this->Html->tag('h2', $this->title);
	}
	if(!empty($this->description))
	{
		echo $this->Html->para('description', $this->description);
	}
echo $this->Html->tag('/div'); //div.box
?>