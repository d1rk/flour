<?php
$widget_path = 'widgets/:type';

//which widget to use, defaults to 'generic'
$type = (isset($type))
	? $type 
	: 'generic'; //TODO: check on generic element

//which template in the widget to use, defaults to 'default'
$template = (isset($template))
	? $template 
	: 'default';

//what data will be passed (at least) to the widget itself
$widget_data = (isset($widget_data))
	? $widget_data 
	: array();

//show a header before the widget?
$header = (isset($header))
	? $header 
	: '';

//show a footer before the widget?
$footer = (isset($footer))
	? $footer 
	: '';

//prepare widget_data
$widget_data = array_merge(
	$widget_data, 
	array(
		'template' => $template,
	)
);

echo $header.$this->element(
	String::insert(
		$widget_path, 
		array(
			'type' => $type,
		)
	),
	$widget_data
).$footer;
?>