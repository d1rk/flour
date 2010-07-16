<div class="container">
	<div class="span-24">

<?php
$nav = array();

	$link = array('controller' => 'docs', 'action' => 'index', 'plugin' => 'flour', 'admin' => false);
	$active = ($this->here == Router::url($link)) ? 'active' : '';
	$nav[] = $this->Html->link( __('Dokumentation', true), $link, array('class' => $active));

	$link = array('controller' => 'docs', 'action' => 'examples', 'plugin' => 'flour', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('Beispiele', true), $link, array('class' => $active));

	$link = array('controller' => 'docs', 'action' => 'api', 'plugin' => 'flour', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav[] = $this->Html->link( __('API', true), $link, array('class' => $active));

echo $this->Html->nestedList($nav);
?>
	</div>
</div>