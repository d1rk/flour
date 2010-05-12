<?php
echo $html->div('', $this->element('nav.user'), array('id' => 'navUser'));
?>
<div id="header">
	<h1><?php
		echo $this->Html->div('crumbs');
			echo $html->link( Configure::read('App.name'), '/');
			$temp = $html->getCrumbs(Configure::read('App.breadcrumb'));
			if(!empty($temp)) echo Configure::read('App.breadcrumb').' '.$temp;
		echo $this->Html->tag('/div'); //div.crumbs
	?></h1>
</div>
<?php
echo $html->div('', $this->element('nav.main'), array('id' => 'navMain'));
?>
