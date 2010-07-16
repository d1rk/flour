<div class="container">
	<div class="span-24">

<?php
$nav = array();

	/* Documentation */
	$link = array('controller' => 'docs', 'action' => 'index', 'plugin' => 'flour', 'admin' => false);
	$active = ($this->here == Router::url($link)) ? 'active' : '';
	$nav_documentation =
		  $this->Html->div($active)
		. $this->Html->div('nav_pre', '')
		. $this->Html->link( __('Dokumentation', true), $link, array('class' => $active))
		. $this->Html->div('nav_post', '')
		. $this->Html->tag('/div');

	/* Examples */
	$link = array('controller' => 'docs', 'action' => 'examples', 'plugin' => 'flour', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav_examples =
		  $this->Html->div($active)
		. $this->Html->div('nav_pre', '')
		. $this->Html->link( __('Beispiele', true), $link, array('class' => $active))
		. $this->Html->div('nav_post', '')
		. $this->Html->tag('/div');

	/* API */
	$link = array('controller' => 'docs', 'action' => 'api', 'plugin' => 'flour', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav_api =
		  $this->Html->div($active)
		. $this->Html->div('nav_pre', '')
		. $this->Html->link( __('API', true), $link, array('class' => $active))
		. $this->Html->div('nav_post', '')
		. $this->Html->tag('/div');


	/* Test 1 */
	$link = array('controller' => 'docs', 'action' => 'examples', 'plugin' => 'flour', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav_test1 =
		  $this->Html->div($active)
		. $this->Html->link( __('Test1 very long text', true), $link, array('class' => $active))
		. $this->Html->tag('/div');

	/* Test 2 */
	$link = array('controller' => 'docs', 'action' => 'api', 'plugin' => 'flour', 'admin' => false);
	$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
	$nav_test2 =
		  $this->Html->div($active)
		. $this->Html->link( __('Test2', true), $link, array('class' => $active))
		. $this->Html->tag('/div');


	$nav = array(
		$nav_documentation,
		$nav_examples,
		$nav_api,
	);

	$nav = array(
		$nav_documentation => array(
			$nav_test1,
			$nav_test2,
			$nav_test1,
		),
		$nav_examples => array(
			$nav_test2,
		),
		$nav_api,
	);


echo $this->Html->nestedList($nav);
?>
	</div>
</div>