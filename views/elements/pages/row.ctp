<?php
$row_template = <<< EOF
<div class="row item clearfix" rel=":Page.id">
	<div class="btnbar actions">:view<br />:edit</div>
	<h3>:Page.title</h3>
</div>
EOF;

echo String::insert($row_template, Set::flatten($row));
?>