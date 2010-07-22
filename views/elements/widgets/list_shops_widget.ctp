<?php
$modelName = 'Shop';
$limitMax = 100;
$limitDefault = 10;
$row_templates = array(
	'default' => __('Default', true),
	'title_only' => __('Title', true),
	'image_only' => __('Only Image', true),
);

/* data-fields */
$data = (isset($data))
	? $data 
	: array();

$order = (isset($data['order']))
	? $data['order']
	: $modelName.'.created DESC';

$limit = (isset($data['limit']))
	? $data['limit']
	: 5;

$conditions = (isset($data['conditions']))
	? $data['conditions']
	: array($modelName.'.status' => 1);

if($template == 'admin')
{

	echo '<div class="input text"><div class="slider"></div></div>';
	echo $this->Form->input('Widget.data.limit', array(
		'type' => 'text',
		'class' => 'inputLimit',
		'default' => '1',
		//TODO: accept only numbers
	));
	
	// echo $this->Form->hidden('Widget.data.limit', array('id' => 'limitAmount'));

	// echo $this->Form->input('Widget.data.limit', array(
	// 	'type' => 'select',
	// 	'options' => array('1' => '1', '2' => '2', '5' => '5', '10' => '10'),
	// ));

	echo $this->Form->input('Widget.data.order', array(
		'type' => 'select',
		'options' => array(
			$modelName.'.created DESC' => __('Latest items first', true),
			$modelName.'.created ASC' => __('Newest items first', true),
		),
	));

	echo $this->Form->input('Widget.data.template', array(
		'type' => 'select',
		'default' => 'default',
		'options' => $row_templates,
	));

	echo $this->Html->scriptBlock("$().ready(function(){
		var limitMax = $limitMax; var limitDefault = $limitDefault;
		$('div.slider').slider({ animate: true, min: 1, max: $limitMax, value: $limitDefault, range: 'min' });
		$('div.slider').bind('slide', function(event, ui) { $('#limitAmount').val(ui.value); $('input.inputLimit').val(ui.value); });
	});");

} else {

$Model = ClassRegistry::init($modelName);
$model_data = $Model->find('all', compact('conditions', 'order', 'limit'));

	echo $this->element('table', array(
		'element' => 'shops/row',
		'data' => $model_data,
		'row_options' => array(
			'template' => $data['template'],
		)
	));
}




// echo $this->Html->scriptBlock('var cakeHere = "'.$this->here.'"; var cakeBase = "'.$this->base.'"; ');
?>