<?php
	$info = array(); $options = array('tag' => 'span', 'escape' => false);

	if($paginator->hasPage(null, 2))
	{
		$info[] = '<div class="btnbar">';
		$info[] = '<ul class="pager">';
		$info[] = '<li class="img">'.$paginator->first($html->image('global/ico/resultset_first.png'), $options).'</li>';
		$info[] = '<li class="img">'.$paginator->prev($html->image('global/ico/resultset_previous.png'), $options).'</li>';
		$info[] = $paginator->numbers(array('separator' => '', 'tag' => 'li'));
		$info[] = '<li class="img">'.$paginator->next($html->image('global/ico/resultset_next.png'), $options).'</li>';
		$info[] = '<li class="img">'.$paginator->last($html->image('global/ico/resultset_last.png'), $options).'</li>';
		$info[] = '</ul>';
		$info[] = '</div>';
	}

	$info[] = '<span>';

	if(!empty($search))
	{
		if(!is_array($search)) $search = array($search);
		$keywords = $text->toList($search, __('</strong> und <strong>', true));
		$info[] = __('Ihre Suche nach <strong>'.$keywords.'</strong> ergab ', true);
	}
	
	switch($paginator->counter(array('format' => '%count%')))
	{
		case 0:
			$info[] = __('keine Ergebnisse.', true); break;
		case 1:
			$info[] = $paginator->counter(array('format' => __('Nur <strong>%count%</strong> Ergebnis.', true))); break;
		case 2:
			$info[] = $paginator->counter(array('format' => __('<strong>%count%</strong> Ergebnisse. Zeige alle beide.', true))); break;
		case 3:
			$info[] = $paginator->counter(array('format' => __('<strong>%count%</strong> Ergebnisse. Zeige alle drei.', true))); break;
		default:
			$info[] = $paginator->counter(array('format' => __('<strong>%count%</strong> Ergebnisse. Zeige <strong>%start%</strong> bis <strong>%end%</strong>.', true)));
	}
	$info[] = '</span>';

	echo $html->div('paginator', join("\n", $info));
?>
