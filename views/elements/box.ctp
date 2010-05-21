<?php
$caption = (isset($caption))
	? $caption
	: '';

$btnbar = (isset($btnbar))
	? $btnbar
	: '';

$description = (isset($description))
	? $description
	: '';

$label = (isset($label))
	? $label
	: '';

$class = (isset($class))
	? $class
	: 'box';

$content = (isset($content))
	? $content
	: '';

$footer = (isset($footer))
	? $footer
	: '';

if (!empty($caption) || !empty($btnbar))
{
	echo $this->Html->div('caption');

		echo (!empty($btnbar) && is_string($btnbar))
			? $this->Html->div('btnbar', $btnbar)
			: null;

		echo (!empty($btnbar) && is_array($btnbar))
			? $this->Html->nestedList($btnbar)
			: null;

		echo (!empty($caption) && is_string($caption))
			? $this->Html->tag('h2', $caption)
			: null;

		echo (!empty($caption) && is_array($caption))
			? $this->Html->nestedList($caption)
			: null;

	echo $this->Html->tag('/div'); //div.caption
}

echo $this->Html->div($class);

	echo (!empty($label))
		? $this->Html->tag('label', $label)
		: null;

	echo (!empty($description))
		? $this->Html->para('description', $description)
		: null;

	echo (!empty($content))
		? $content
		: null;

	echo (!empty($footer))
		? $this->Html->div('footer', $footer)
		: null;

echo $this->Html->tag('/div'); //div.box
?>