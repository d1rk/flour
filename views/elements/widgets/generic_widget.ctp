<?php
/* data-fields */
$data = (isset($data))
	? $data 
	: array();

$default = (isset($data['template']))
	? $data['template']
	: '';

$models = App::objects('model');
//create an array with each model as key and (!) value (so the modelname gets saved, not the index)
foreach ($models as $key => $val)
{
	unset($models[$key]);
	$models[$val] = $val;
}

if($template != 'admin' && in_array($data['model'], $models))
{
	$Model = ClassRegistry::init($data['model']);
	$model_data = $Model->findById($data['id']);
	$data = array_merge($data, $model_data);
	$data['img'] = Router::url('/img');
	$data['base'] = $this->base;
}

$admin = array();
$admin[] = $this->Form->input('Widget.data.model', array(
	'type' => 'select',
	'options' => $models,
));
$admin[] = $this->Form->input('Widget.data.id', array(
	'type' => 'text',
));
$admin[] = $this->Form->input('Widget.data.template', array(
	'type' => 'textarea',
	'default' => '<h3>:Model.name</h3>',
));
$admin = implode($admin);


echo String::insert($$template, Set::flatten($data));
?>