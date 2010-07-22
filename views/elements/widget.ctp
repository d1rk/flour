<?php

//which widget to use, defaults to 'generic'
$type = (isset($type))
	? $type 
	: 'generic';

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


echo $header;
echo $this->element(
	String::insert(Configure::read('App.Widget.pattern'), array('type' => $type)),
	array(
		'data' => $widget_data,
		'template' => $template,
	)
);
echo $footer;
?>