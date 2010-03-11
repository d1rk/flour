<?php
//here will show up links, that relate to the current active section.
$links = (!isset($links)) ? array() : $links;

if(!empty($links))
{
	echo $this->Html->div('navSub', $this->Html->nestedList($links));
}
?>