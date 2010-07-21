<?php
$template = <<<HTML
<div class="item clearfix" rel=":Widget.id">
	<h3>:Widget.name</h3>
</div>
HTML;

echo String::insert($template, Set::flatten($row));
?>