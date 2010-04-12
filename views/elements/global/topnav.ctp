<div id="topnav">
	<div class="container">
	<ul>
<?php
		$links = array(
			'brünsicke.com' => 'http://bruensicke.com/',
			'd1rk.com' => 'http://d1rk.com/',
			'websamurai.de' => 'http://www.websamurai.de/',
		);
		foreach($links as $name => $url)
		{
			$active = (stristr($url, $_SERVER['SERVER_NAME'])) ? ' class="active"' : '';
			echo "\t\t".'<li'.$active.'><a href="'.$url.'">'.$name.'</a></li>'."\n";
		}
?>
	</ul>
	</div>
</div>