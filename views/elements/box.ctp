<?php
$caption = (isset($caption))
	? $caption
	: '';

$btnbar = (isset($btnbar))
	? $btnbar
	: '';

$filters = (isset($filters))
	? $filters
	: '';

$actions = (isset($actions))
	? $actions
	: '';

$description = (isset($description))
	? $description
	: '';

$label = (isset($label))
	? $label
	: '';

$class = (isset($class))
	? $class
	: 'box clearfix';

$style = (isset($style))
	? $style
	: null;

$options = (isset($options))
	? $options
	: array('style' => $style);

$content = (isset($content))
	? $content
	: '';

$footer = (isset($footer))
	? $footer
	: '';

if (!empty($caption) || !empty($btnbar) || !empty($filters))
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

		echo (!empty($actions) && is_string($actions))
			? $this->Html->div('actions', $actions)
			: null;

		echo (!empty($actions) && is_array($actions))
			? $this->Html->div('actions', $this->Html->nestedList($actions))
			: null;

		echo (!empty($filters) && is_string($filters))
			? $this->Html->div('filter', $filters)
			: null;

		echo (!empty($filters) && is_array($filters))
			? $this->Html->div('filter', $this->Html->nestedList($filters))
			: null;

	echo $this->Html->tag('/div'); //div.caption
}

echo $this->Html->div($class, null, $options);

	echo (!empty($label))
		? $this->Html->tag('label', $label)
		: null;

	echo (!empty($description))
		? $this->Html->para('description', $description)
		: null;

	echo (!empty($content) && is_string($content))
		? $content
		: null;

	echo (!empty($content) && is_array($content))
		? implode($content)
		: null;

	echo (!empty($footer))
		? $this->Html->div('footer', $footer)
		: null;

echo $this->Html->tag('/div'); //div.box
?>