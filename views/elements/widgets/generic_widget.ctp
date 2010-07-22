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

if($template != 'admin' && isset($data['model']) && in_array($data['model'], $models))
{
	$Model = ClassRegistry::init($data['model']);
	$model_data = $Model->findById($data['id']);
	$data = array_merge($data, $model_data);
	$data['img'] = Router::url('/img');
	$data['base'] = $this->base;
}

/*
if($template == 'json' && isset($data['model']))
{
	$Model = ClassRegistry::init($data['model']);
	$model_data = $Model->find('list');
	echo json_encode($model_data);
	exit;
}
*/

if($template == 'idselect' && isset($this->passedArgs['model']))
{
	$Model = ClassRegistry::init($this->passedArgs['model']);
	$model_data = $Model->find('list');

	echo $this->Form->input('Widget.data.id', array(
		'type' => 'select',
		'options' => $model_data,
	));
	exit;
}

$admin = array();
$admin[] = $this->Form->input('Widget.data.model', array(
	'type' => 'select',
	'options' => $models,
	'class' => 'WidgetDataModelSelect',
));

$admin[] = $this->Form->input('Widget.data.id', array(
	'type' => 'text',
	'before' => '<div class="input text idselect">',
	'after' => '</div>',
	'div' => false,
));

$admin[] = $this->Form->input('Widget.data.template', array(
	'type' => 'textarea',
	'default' => '<h3>:Model.name</h3>',
));
$admin = implode($admin);


echo String::insert($$template, Set::flatten($data));



echo $this->Html->scriptBlock('var cakeHere = "'.$this->here.'"; var cakeBase = "'.$this->base.'"; ');
if($template == 'admin')
{
echo <<<HTML
<script type="text/javascript">
$().ready(function()
{
	$('.WidgetDataModelSelect').change(function() {
		var modelType = $(this).attr('value');
		if(modelType=='') {
			$('div.idselect').html('');
		} else {
			$.get(
				cakeBase+"/admin/flour/widgets/get/type:generic/template:idselect/model:"+modelType,
				function(data) {
					$('div.idselect').html(data);
				}
			);
		}
	});
});
</script>
HTML;
}
?>