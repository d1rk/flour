<?php
$modelName = (isset($this->params['models'][0]))
	? $this->params['models'][0]
	: null;

if(isset($modelName) && !isset($this->Model))
{
	$this->Model = ClassRegistry::init($modelName);
	$displayField = $this->Model->displayField;
}

$displayField = (isset($this->Model->displayField))
	? $this->Model->displayField
	: 'name';

echo $this->Html->div('box');
	echo $this->Html->tag('h2', $row[$modelName][$displayField]);
echo $this->Html->tag('/div'); //div.box
?>