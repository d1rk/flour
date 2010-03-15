<?php
$template = (isset($template)) ? $template : $this->element($element);

$rows = array();
$i = 0;

foreach($data as $ind => $row)
{
	$rows[] = $this->element($item, array('row' => $row, 'i' => $i));
}
$content = $html->div('empty stripes', $html->div('inner', $html->div('panel', $html->tag('h2', __('Keine Ergebnisse', true)))));

//needed for daterange picker
//echo $javascript->link(array('global/jquery/daterange'));
//echo $html->css(array('global/jquery/daterange'));

//searchform + daterange
echo $form->create($model, array('action' => 'search'));

echo $html->div('panel', null, array('style' => 'border-bottom: 1px solid #E6E6E6;'));
	echo $html->div('btnbar', null, array('style' => 'padding: 4px;'));

		//input for search
		if(!isset($current_searchterms)) $current_searchterms = '';
		if(!empty($current_searchterms)) echo '<span>'.$html->link( __('reset', true), array('action' => $this->action)).'</span>';
		echo $form->input($model.'.search', array('label' => false, 'value' => $current_searchterms, 'class' => 'search', 'div' => false, 'title' => __('Search', true)));

	echo $html->tag('/div')."\n";

	//input for date
	$date_options = array('class' => 'daterange', 'div' => false, 'label' => false, 'autocomplete' => 'off');
	if(!empty($this->params['named']['date'])) $date_options['value'] = $this->params['named']['date'];
	echo $html->div('daterange', $form->input($model.'.date', $date_options));

	//prepare filters
		$merge = array(); //prepare an array that will fit together search-conditions
		if(!empty($this->params['named']['search'])) $merge['search'] = $this->params['named']['search']; //add searchterm, if entered
		if(!empty($this->params['named']['date'])) $merge['date'] = $this->params['named']['date']; //add from_date, if entered

		//add filters
		/*
		$filter[] = $html->link( __('Alle Aufträge', true), array_merge($merge, array('controller' => 'orders', 'action' => 'index')), array('class' => (empty($this->params['named'])) ? 'active' : null));
		$filter[] = $html->link( __('übertragene Aufträge', true), array_merge($merge, array('controller' => 'orders', 'action' => 'index', 'status' => '2,3,4,5,6')), array('class' => (!empty($this->params['named']['status'])) ? 'active' : null));
		$filter[] = $html->link( __('unfertige Aufträge', true), array_merge($merge, array('controller' => 'orders', 'action' => 'index', 'status' => '0')), array('class' => (array_key_exists('status', $this->params['named']) && $this->params['named']['status'] == 0) ? 'active' : null));
		$filter[] = $html->link( __('Aufträge mit Produkten', true), array_merge($merge, array('controller' => 'orders', 'action' => 'index', 'product' => '1')), array('class' => (!empty($this->params['named']['product'])) ? 'active' : null));
		$filter[] = $html->link( __('stornierte Aufträge', true), array_merge($merge, array('controller' => 'orders', 'action' => 'index', 'Order.deleted' => '1')), array('class' => (!empty($this->params['named']['Order.deleted'])) ? 'active' : null));
		*/
		
	echo $html->div('input'); //TODO: add model-class
		//echo $html->tag('label', 'Aufträge'); //TODO: label

		if(isset($filter)) echo $html->tag('span', $html->nestedList($filter), array('class' => 'filter'));

		$content = (count($data) > 0) ? str_replace('{{rows}}', join("\n", $rows), $template) : $content;
		if(!empty($current_searchterms)) $content = $text->highlight($content, $current_searchterms, '<span class="highlight">\1</span>', true);
		echo $html->div('items', $content);
		echo $html->div('paging', $this->element('paging', array('search' => $current_searchterms)));
	echo $html->tag('/div')."\n";
echo $html->tag('/div')."\n";

echo $html->div('hide', $form->submit( __('Go', true), array('class' => 'btnEnter')));
echo $form->end();

?>