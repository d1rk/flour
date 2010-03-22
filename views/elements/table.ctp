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
echo $this->Form->create('', array('action' => 'search'));

echo $this->Html->div('panel');

	if(!empty($caption))
	{
		echo $this->Html->div('caption', $caption);
	}

	//input for search
	if(!empty($search))
	{
		echo $this->Html->div('btnbar', null, array('style' => 'padding: 4px; margin-right: 6px;'));

			if(!empty($current_searchterms))
			{
				echo $this->Html->tag('span', $this->Html->link( __('reset', true), array('action' => $this->action)));
			}
			echo $this->Form->hidden('Model.name', array('value' => $search));
			echo $this->Form->input('search', array(
				'label' => false,
				'value' => $current_searchterms,
				'class' => 'search',
				'div' => false,
				'title' => __('Search', true),
			));
		echo $this->Html->tag('/div')."\n";
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
		echo $this->Html->div('daterange', $this->Form->input($model.'.date', $date_options));
	}

	//filters
	if(!empty($filters))
	{
		//prepare filters
		$merge = $filter = array(); //prepare an array that will fit together search-conditions
		if(!empty($this->params['named']['search'])) $merge['search'] = $this->params['named']['search']; //add searchterm, if entered
		if(!empty($this->params['named']['date'])) $merge['date'] = $this->params['named']['date']; //add from_date, if entered

		foreach($filters as $name => $link)
		{
			$active = (Router::url($link) == $this->here) ? 'active' : null;
			$filter[] = $this->Html->link( $name, array_merge($merge, $link), array('class' => $active));
		}
	}

	//rendr input
	echo $this->Html->div('input'); //TODO: add model-class

		if(!empty($label))
		{
			echo $this->Html->tag('label', $label);
		}

		if(!empty($filter))
		{
			echo $this->Html->tag('span', $this->Html->nestedList($filter), array('class' => 'filter'));
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
			//highlights the searchterm in output
			$content = $this->Text->highlight(
				$content,
				$current_searchterms,
				array(
					'format' => '<span class="highlight">\1</span>', //format of replace
					'html' => true, //will take care of html
				));
		}

		echo $this->Html->div('items', $content);

		//paginator
		if(isset($this->Paginator))
		{
			echo $this->Html->div('paging', $this->element('paging', array('search' => $current_searchterms)));
		}

	echo $this->Html->tag('/div')."\n"; //div.input
echo $this->Html->tag('/div')."\n"; //div.panel


echo $this->Html->div('hide', $this->Form->submit( __('Go', true), array('class' => 'btnEnter')));
echo $this->Form->end();

?>