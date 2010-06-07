<?php
$template = <<<HTML
<div class="item clearfix" rel=":Content.id">
	<h3>:Content.name</h3>
</div>
HTML;

echo String::insert($template, Set::flatten($row));
?>