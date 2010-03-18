<?php
$element = (isset($element))
	? $element 
	: 'generic'; //TODO: check on generic element

$header = (isset($header))
	? $header 
	: '';

$footer = (isset($footer))
	? $footer 
	: '';

$data = (isset($data))
	? $data
	: array();

$empty = (isset($empty))
	? $empty
	: $this->element('empty');

$search = (isset($search))
	? $search
	: null;

$current_searchterms = (isset($current_searchterms))
	? $current_searchterms
	: '';

$date = (isset($date))
	? $date
	: null;

$filters = (isset($filters))
	? $filters
	: null;

$label = (isset($label))
	? $label
	: null;

$caption = (isset($caption))
	? $caption
	: null;

$template = (isset($template))
	? $template
	: '{{rows}}';


/* BEGIN OF RENDERING */


//needed for daterange picker
//echo $this->Html->script(array('global/jquery/daterange'));
//echo $this->Html->css(array('global/jquery/daterange'));

//searchform + daterange
echo $form->create('', array('action' => 'search'));

echo $html->div('panel');

	if(!empty($caption))
	{
		echo $this->Html->div('caption', $caption);
	}

	//input for search
	if(!empty($search))
	{
		echo $html->div('btnbar', null, array('style' => 'padding: 4px; margin-right: 6px;'));

			if(!empty($current_searchterms))
			{
				echo $this->Html->tag('span', $html->link( __('reset', true), array('action' => $this->action)));
			}
			echo $form->input('search', array(
				'label' => false,
				'value' => $current_searchterms,
				'class' => 'search',
				'div' => false,
				'title' => __('Search', true),
			));
		echo $html->tag('/div')."\n";
	}

	//input for date
	if(!empty($date))
	{
		$date_options = array(
			'class' => 'daterange',
			'div' => false,
			'label' => false,
			'autocomplete' => 'off'
		);
		if(!empty($this->params['named']['date'])) $date_options['value'] = $this->params['named']['date'];
		echo $html->div('daterange', $form->input($model.'.date', $date_options));
	}
	//input for date

	if(!empty($filters))
	{
		//prepare filters
		$merge = $filter = array(); //prepare an array that will fit together search-conditions
		if(!empty($this->params['named']['search'])) $merge['search'] = $this->params['named']['search']; //add searchterm, if entered
		if(!empty($this->params['named']['date'])) $merge['date'] = $this->params['named']['date']; //add from_date, if entered

		foreach($filters as $name => $link)
		{
			$filter[] = $html->link( $name, array_merge($merge, $link), array('class' => (empty($this->params['named'])) ? 'active' : null));
		}
	}

	echo $html->div('input'); //TODO: add model-class

		if(!empty($label))
		{
			echo $html->tag('label', $label);
		}

		if(!empty($filter))
		{
			echo $html->tag('span', $html->nestedList($filter), array('class' => 'filter'));
		}

		//rows
		if(count($data))
		{
			$rows = array();
			$i = 0;
			
			foreach($data as $ind => $row)
			{
				$row = (isset($prefix)) ? array($prefix => $row) : $row;
				$rows[] = $this->element($element, array('row' => $row, 'i' => $i++, 'even' => ($i % 2) ? 'even' : 'odd'));
			}

			//insertion of item-template in main-template
			$connector = (Configure::read()) ? "\n" : '';
			$content = $header.str_replace('{{rows}}', implode($connector, $rows), $template).$footer;
	
		} else {
			$content = $empty;
		}

		if(!empty($current_searchterms))
		{
			$content = $text->highlight($content, $current_searchterms, '<span class="highlight">\1</span>', true);
		}

		echo $html->div('items', $content);

		//paginator
		if(isset($this->Paginator))
		{
			echo $html->div('paging', $this->element('paging', array('search' => $current_searchterms)));
		}

	echo $html->tag('/div')."\n"; //div.input
echo $html->tag('/div')."\n"; //div.panel


echo $html->div('hide', $form->submit( __('Go', true), array('class' => 'btnEnter')));
echo $form->end();

?>