<?php
$row_template = <<< EOF
<div class="row item clearfix">
	<div class="btnbar actions">:view<br />:edit</div>
	<h3>:name</h3>
	:notes
</div>
EOF;

$data = array();
$data['counter'] = $i;
$data['even'] = $even;
$data['created'] = $html->tag('span', date('d.m.', strtotime($row['Client']['created'])), array('title' => $row['Client']['created']));
$data['modified'] = $html->tag('span', date('d.m.', strtotime($row['Client']['modified'])), array('title' => $row['Client']['modified']));

$data['name'] = $this->Html->link(
	$row['CustomList']['name'], 
	array('controller' => 'custom_lists', 'action' => 'view', $row['CustomList']['id'])
);

$data['notes'] = (!empty($row['CustomList']['notes'])) ? $this->Html->para('notes', $row['CustomList']['notes']) : '';

$data['view'] = $this->Button->link(
	__('view', true),
	array('controller' => 'custom_lists', 'action' => 'view', $row['CustomList']['id']),
	array(
		'title' => __('show this custom list', true),
		'ico' => 'page_white_magnify',
	)
);

$data['edit'] = $this->Button->link(
	__('edit', true),
	array('controller' => 'custom_lists', 'action' => 'edit', $row['CustomList']['id']),
	array(
		'title' => __('edit this custom list', true),
		'ico' => 'page_white_edit',
	)
);

$data = Set::flatten(array_merge($row, $data)); //merge above data with all data and flatten it
krsort($data);

echo String::insert($row_template, $data); //replace placeholders in template //TODO: fix rsort

?>