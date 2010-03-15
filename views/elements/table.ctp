<?php
$element = (isset($element)) 
	? $element 
	: 'generic'; //TODO: check on genric element

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

$main_template = (isset($main_template))
	? $main_template
	: '{{rows}}';


/* BEGIN OF RENDERING */


//needed for daterange picker
//echo $javascript->link(array('global/jquery/daterange'));
//echo $html->css(array('global/jquery/daterange'));

//searchform + daterange
echo $form->create('', array('action' => 'search'));

echo $html->div('panel', null, array('style' => 'border-bottom: 1px solid #E6E6E6;'));

	//input for search
	if(!empty($search))
	{
		echo $html->div('btnbar', null, array('style' => 'padding: 4px;'));

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

	//input for search
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
				$rows[] = $this->element($element, array('row' => $row, 'i' => $i));
			}

			//insertion of item-template in main-template
			$connector = (Configure::read()) ? "\n" : '';
			$content = $header.str_replace('{{rows}}', implode($connector, $rows), $main_template).$footer;
	
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