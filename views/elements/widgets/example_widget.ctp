<?php
$data = (isset($data))
	? $data 
	: array();
	

$default = <<<HTML
<p>:content</p>
HTML;

$h3 = <<<HTML
<div>
	<h3>:content</h3>
</div>
HTML;

$admin = <<<HTML

HTML;

echo String::insert($$template, Set::flatten($data));
?>