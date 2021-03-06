<?php
Configure::write('App.name', '');
Configure::write('App.version', '0.1');
Configure::write('App.tagline', '');

//layout / theme specific
Configure::write('App.theme', 'default'); //default theme
Configure::write('App.title', ':title - :name (:version)'); //used for title-tag
Configure::write('App.keywords', '');
Configure::write('App.description', '');
Configure::write('App.breadcrumb', ' &raquo; '); //used as seperator for breadcrumbs

//Widgets
Configure::write('App.Widget.pattern', 'widgets/:type_widget');
Configure::write('App.Widget.types', array(
	'generic' => __('Generic Model', true),
	'example' => __('Example', true),
	'html' => __('Html block', true),
	'list_shops' => __('Shop list', true),
));

//Contents
Configure::write('App.Content.types', array(
	'example' => __('Example', true),
));
?>