<?php
$model = (isset($model))
	? $model
	: $this->params['models'][0];

$title = (isset($title))
	? $title
	: Inflector::humanize(Inflector::pluralize($model));

$columns = (isset($columns))
	? $columns
	: array();

$searchitems = (isset($searchitems))
	? $searchitems
	: $columns;

$buttons = (isset($buttons))
	? $buttons
	: array(
		array('name' => __('Add', true), 'bclass' => 'btnAdd', 'onpress' => 'add'),
		array('name' => __('Edit', true), 'bclass' => 'btnEdit', 'onpress' => 'edit'),
		array('name' => __('Delete', true), 'bclass' => 'btnDelete', 'onpress' => 'delete'),
	);


#debug($model);
#debug($this->params);


$modelInstance = ClassRegistry::init($model);


if(empty($columns))
{
	$schema = $modelInstance->schema();
	#debug($schema);
	foreach ($schema as $key => $value) {
		$columns[] = array(
			'display' => Inflector::humanize($key),
			'name' => $model.'.'.$key,
			'width' => 200,
			'sortable' => true,
			'align' => 'left',
		);
	}
}
$ffkeys = $modelInstance->__collectForeignKeys();
#debug(array_flip($ffkeys));
#debug($columns);

list($sortname, $sortorder) = explode(' ', $this->params['paging'][$model]['options']['order'], 2);

echo $this->Html->tag('table', '', array('class' => 'grid', 'style' => 'display: none;'));

echo $this->Html->css('/flour/css/flexigrid');
echo $this->Html->script('/flour/js/jquery/flexigrid');
echo $this->Html->scriptBlock("
$().ready(function(){
function test(){};

$('.grid').flexigrid({
	url: '".$this->base."/admin/items/index',
	dataType: 'json',
	colModel : ".json_encode($columns).",
	buttons : ".json_encode($buttons).",
	searchitems : ".json_encode($columns).",
/*	buttons : [
		{name: 'Add', bclass: 'add', onpress : test},
		{name: 'Delete', bclass: 'delete', onpress : test},
		{separator: true}
		],
	searchitems : [
		{display: 'ISO', name : 'iso'},
		{display: 'Name', name : 'name', isdefault: true}
		],
*/	sortname: '".$sortname."',
	sortorder: '".$sortorder."',
	usepager: true,
	title: '".$title."',
	useRp: true,
	rp: ".$this->params['paging'][$model]['options']['limit'].",
	showTableToggleBtn: true,
	width: 950,
	height: 500
});



});
");
?>