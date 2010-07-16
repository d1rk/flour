<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php
	echo $this->Html->charset();
	echo $this->Html->tag('title', $title_for_layout);
	echo $this->Html->meta('icon');

	echo $this->Html->css(array(
		'/flour/css/blueprint',
		'/flour/css/docs',
	));
	echo $this->Html->script(array(
		'/flour/js/jquery/jquery',
		'/flour/js/docs'
	));

	echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?php echo $this->element('docs/header'); ?>
		</div>
		<div id="nav">
			<?php echo $this->element('docs/nav'); ?>
		</div>
		<div id="content" class="span-24">
			<?php
			echo $this->Session->flash();
			echo $content_for_layout;
			?>
		</div>
		<div id="breadcrumb">
			<?php echo $this->element('docs/breadcrumb'); ?>
		</div>
		<div id="footer">
			<?php echo $this->element('docs/footer'); ?>
		</div>
		<div id="copyright">
			<?php echo $this->element('docs/copyright'); ?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>