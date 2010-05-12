<?php
echo $this->Html->div('', $this->element('nav.user'), array('id' => 'navUser'));
?>
<div id="header">
	<h1><?php
		echo $this->Html->div('crumbs');
			echo $this->Html->link( Configure::read('App.name'), '/');
			$temp = $this->Html->getCrumbs(Configure::read('App.breadcrumb'));
			if(!empty($temp)) echo Configure::read('App.breadcrumb').' '.$temp;
		echo $this->Html->tag('/div'); //div.crumbs
	?></h1>
</div>
<?php
echo $this->Html->div('', $this->element('nav.main'), array('id' => 'navMain'));
?>
