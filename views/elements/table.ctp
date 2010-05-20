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
	: $this->data;

$empty = (isset($empty))
	? $empty
	: $this->element('empty');

$search = (isset($search))
	? $search
	: null;

$preserveNamedParams = (isset($preserveNamedParams))
	? $preserveNamedParams
	: true;

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

$btnbar = (isset($btnbar))
	? $btnbar
	: null;

$template = (isset($template))
	? $template
	: '{{rows}}';


/* BEGIN OF RENDERING */
$box_content = $btnbar_content = array();

//needed for daterange picker
//echo $this->Html->script(array('global/jquery/daterange'));
//echo $this->Html->css(array('global/jquery/daterange'));

//searchform + daterange
//TODO: switch for search-form
if(!empty($search))
{
	echo $this->Form->create('', array('action' => 'search'));
}







	if(!empty($search))
	{
		if(!empty($current_searchterms))
		{
			$url = array('action' => $this->action);
			if($preserveNamedParams && isset($this->params['named'])) {
				$params = $this->params['named'];
				unset($params['search']);
				$url = array_merge($url, $params);
			}
			$btnbar_content[] = $this->Html->tag('span', $this->Html->link( __('reset', true), $url));
		}

		$btnbar_content[] = '&nbsp;'; //needed for placement in caption (line-height)
		$btnbar_content[] = $this->Form->hidden('Model.name', array('value' => $search));
		if($preserveNamedParams && isset($this->params['named']) && !empty($this->params['named']))
		{
			$btnbar_content[] = $this->Form->hidden('Model.params', array('value' => json_encode($this->params['named'])));
		}
		$btnbar_content[] = $this->Form->input('search', array(
			'label' => false,
			'value' => $current_searchterms,
			'class' => 'search',
			'div' => false,
			'title' => __('Search', true),
		));
		$btnbar_content[] = '&nbsp;'; //needed for placement in caption (line-height)
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
			$active = (Router::url(array_merge($link, $merge)) == $this->here) ? 'active' : null;
			$filter[] = $this->Html->link( $name, array_merge($link, $merge), array('class' => $active));
		}
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

	$box_content[] = $this->Html->div('items', $content);

	//paginator
	$footer = (isset($this->Paginator))
		? $this->Html->div('footer', $this->element('paging', array('search' => $current_searchterms)))
		: null;

	$btnbar_content[] = $btnbar;
	$btnbar_content = implode($btnbar_content);

echo $this->element('box', array(
	'caption' => $caption,
	'btnbar' => $btnbar_content,
	'label' => (!empty($label))
		? $label
		: null,
	'content' => implode($box_content),
	'footer' => $footer,
));



if(!empty($search))
{
	echo $this->Html->div('hide', $this->Form->submit( __('Go', true), array('class' => 'btnEnter')));
	echo $this->Form->end();
}

?>