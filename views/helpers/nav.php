<?php
/*
 *
 * Example Usage:
 *
 * echo $nav->show('slug'); //reads navigation with name of "slug" from Navigationtable
 *
 * $slugi = $nav->create('Page View', array('description' => 'All Navigations for editing a page.', 'cssclass' => 'nav horiz'));
 * $nav->add($slugi, array('name' => 'Contents', 'url' => '#contents', 'cssclass' => 'content'));
 * $nav->add($slugi, 'Areas', '#areas');
 *
 * TODO: caching! :)
 *
 */
class NavHelper extends AppHelper
{
	var $navObject = false;
	var $navModel = 'Navigation';
	var $itemModel = 'Navigationitem';

	var $pluginUse = false;
	var $pluginName = 'flour';

	var $tab = "\t";
	var $cssfield = 'cssclass';
	var $itemName = 'name';
	var $itemLink = 'url';
	var $pageId   = 'page_id';

	var $allowedAttributes = array(
		'id',
		'class',
		'rel',
		'title',
		'ico', //special case, used by button-helper
		'onclick',
		'onchange',
		'onfocus',
		'onhover',
		'ondblclick',
		'confirm',
	);

	var $texts = array(
			'moveup' => '[+]',
			'movedown' => '[-]',
			'editlink' => 'edit',
			'deletelink' => 'delete',
			'deleteconfirm' => 'Are you sure?',
			'ispage' => '(page)',
		);

	var $admin = false;

	var $cNavis = array(); //cache Navigations
	var $cItems = array(); //cache Navigationitems

	//stores all settings to build this very current navigationcontainer, based on settings given in config-arr.
	//these settings can be defaultet to other presets using the mode-param. see below.
	var $config = array(
			'title' => false, //shows title in front of navigation
			'div' => false, //surrounds navigation with a div
			'divclass' => 'navbar', //default css-class for surrounding div

		);

	var $helpers = array(
		'Html',
		'Form',
		'Flour.Button'
	);

	//with mode you can bundle a set of config-params to a named preset.
	//You just have to call this preset and can even overwrite specific vars in config-array.
	//WARNING: At the moment, you have to have ALL possible params in each preset! //TODO: change that! :)
	var $mode = array(
				'default' => array(
						'title' => false,
						'div' => false,
						'divclass' => 'navbar',
						'ulclass' => '',
						'show_page' => false,
						'show_edit' => false,
						'sortbuttons' => false,
						'sortable' => false,
					),
				'admin'=> array(
						'title' => true,
						'div' => false,
						'divclass' => 'navbar',
						'ulclass' => '',
						'show_page' => true,
						'show_edit' => true,
						'sortbuttons' => false,
						'sortable' => false,
					),
				'main' => array(
						'title' => false,
						'div' => true,
						'divclass' => 'navbar',
						'ulclass' => '',
						'show_page' => false,
						'show_edit' => false,
						'sortbuttons' => false,
						'sortable' => false,
					),
				'primary' => array(
						'title' => false,
						'div' => true,
						'divclass' => 'btnbar navSub',
						'ulclass' => '',
						'show_page' => false,
						'show_edit' => false,
						'sortbuttons' => false,
						'sortable' => false,
					),
				'secondary' => array(
						'title' => false,
						'div' => true,
						'divclass' => 'btnbar navSub',
						'ulclass' => '',
						'show_page' => false,
						'show_edit' => false,
						'sortbuttons' => false,
						'sortable' => false,
					),
				'sort'=> array(
						'title' => false,
						'div' => false,
						'divclass' => 'navbar sortable',
						'ulclass' => 'sortable',
						'show_edit' => false,
						'show_page' => true,
						'sortbuttons' => true,
						'sortable' => true,
					),
		);

	//TODO: check for nav::exist, count(items), isAdmin?
	function show($name, $config = array()) //TODO: $options array :)
	{
		if(is_string($config)) $config = array('mode' => $config);
		$mode = array_key_exists('mode', $config) ? $config['mode'] : 'default'; //see, which mode is given.
		$this->config = array_merge($this->mode[$mode], $config); //setup this mode
		if(!array_key_exists($name, $this->cNavis) )
		{
			$this->cItems[$name] = Cache::read('flour.nav.'.$name.'.items'); //should work, otherwise, it will fail in next if
			if(!$this->cNavis[$name] = Cache::read('flour.nav.'.$name.'.nav'))
			{
				$this->_init();
				//$tmp = (WifePlugin::isActive('cms')) ? $this->navObject->findbySlug($name, null, null, 0) : null;
				if(empty($tmp))
				{
					$this->cNavis[$name] = array($this->cssfield => '', 'name' => Inflector::humanize($name));
					$this->cItems[$name] = array();
				} else {
					$this->cNavis[$name] = $tmp[$this->navModel];
					$this->cItems[$name] = $this->navObject->{$this->itemModel}->find('threaded', array('order' => $this->itemModel.'.lft', 'conditions' => array($this->itemModel.'.navigation_id' => $tmp[$this->navModel]['id'])));
				}

				if(!Configure::read())
				{
					Cache::write('flour.nav.'.$name.'.nav', $this->cNavis[$name], 'forever');
					Cache::write('flour.nav.'.$name.'.items', $this->cItems[$name], 'forever');
				}
				unset($tmp);
			} else {
				return false;
			}

		}
		return $this->show_links($name);
	}


	function show_links($name)
	{
		//we better check that.
		if(!array_key_exists($name, $this->cItems))
		{
			$this->cNavis[$name] = array($this->cssfield => '', 'name' => Inflector::humanize($name));
			$this->cItems[$name] = array();
		}
		if($this->config['div'])
		{
			$output = $this->Html->div($this->config['divclass'], $this->list_element_links($name, $this->cItems[$name]));
		} else {
			$output = $this->list_element_links($name, $this->cItems[$name]);
		}
#		debug($output);
		return $this->output($output);
	}

	function list_element_links($name, $data, $level = 0)
	{
		$tabs = "\n" . str_repeat($this->tab, $level * 2);
		$li_tabs = $tabs . $this->tab;
		$output = '';

		//show title?
		if($level == 0 && $this->config['title'] && array_key_exists('name', $this->cNavis[$name]))
		{
			$output .=  '<h3>'.$this->cNavis[$name]['name'].'</h3>';
		}

		//insert css?
		$ulclass = '';
		if($level == 0)
		{
			$ulcss = $this->cNavis[$name][$this->cssfield];
			if(array_key_exists('ulclass', $this->config) && !empty($this->config['ulclass'])) $ulclass .= ' '.$this->config['ulclass'];
			if(!empty($ulclass)) $ulclass = ' class="'.$ulclass.'"'; //put into html-string
		}
		$output .= $tabs. '<ul'.$ulclass.' id="parent-'.$level.'">';


		//show admin-links?
		if($this->admin) //this user can add links, so we should
		{
			$navlink = ($this->pluginUse) ? '/'.$this->pluginName : ''; //prepend pluginname, if needed
			$navlink .= '/navigations/additem/'.$name;
			//$output .= $li_tabs . '<li class="admin">'.$this->Html->link($this->image('ico/add.png'), $navlink, array('class' => 'modal', 'escape' => false)).'</li>'; //imagelink
			$output .= $li_tabs . '<li class="admin">'.$this->Html->link('add link', $navlink, array('class' => 'modal')).'</li>'; //textlink
		}

		$slug = $name;

		//iterate all items
		$i = 0;
		foreach ($data as $key => $val)
		{
			$i++;
			extract($val[$this->itemModel]);

			$class = (isset($class))
				? $class
				: '';
			
			$class = explode(' ', $class);

			if($i == 1) $class[] = 'first';
			if($i == count($data)) $class[] = 'last';
			if(isset($url) && stristr($this->here, Router::url($url))) $class[] = 'active'; #$active = (stristr($this->here, Router::url($link))) ? 'active' : '';

			$attributes = $val[$this->itemModel];
			foreach($attributes as $key => $value)
			{
				if(!in_array($key, $this->allowedAttributes)) unset($attributes[$key]);
			}

			$attributes['id'] = (!isset($attributes['id']) && isset($id))
				? 'item_'.$slug.'_'.$id
				: null;

			$attributes['rel'] = (isset($id))
				? $id
				: null;

			$attributes['class'] = implode(' ', $class);

			$output .= $li_tabs.$this->Html->tag('li', null, $attributes);

			//show sort-buttons?
			if($this->config['sortbuttons'])
			{
				$output .= ' '.$this->Html->link($this->texts['moveup'], array('plugin' => 'cms', 'controller' => 'navigations', 'action' => 'moveitem', $id, 'up'), array('class' => 'ajax')).' ';
				$output .= ' '.$this->Html->link($this->texts['movedown'], array('plugin' => 'cms', 'controller' => 'navigations', 'action' => 'moveitem', $id, 'down'), array('class' => 'ajax')).' ';
			}

			//show edit-link?
			if($this->config['show_edit'])
			{
				$output .= ' ('.$this->Html->link($this->texts['editlink'], array('plugin' => 'flour', 'controller' => 'navigations', 'action' => 'edititem', $id), array('class' => 'modal')).') ';
				$output .= ' ('.$this->Html->link($this->texts['deletelink'], array('plugin' => 'flour', 'controller' => 'navigations', 'action' => 'removeitem', $id), array('class' => 'ajax'), $this->texts['deleteconfirm']).') ';
			}

			//the link itself, yeeha
			
			$type = (isset($type))
				? Inflector::humanize($type)
				: 'Html';

			if(empty($url)) $_rel = (!empty($rel)) ? ' rel="'.$rel.'"' : '';

			$url = (isset($url) && !empty($url))
				? $url
				: false;

			switch($type)
			{
				case 'Button':
					$output .= $this->Button->button('<span'.$_rel.'>'.$name.'</span>', $attributes);
					break;
				case 'Link':
					$output .= $this->Button->link($name, $url, $attributes);
					break;
				case 'Html':
				default:
					if($url)
					{
						$output .= $this->Html->link($name, $url, $attributes);
					} else {
						$output .= $this->Html->tag('span', $name, $attributes);
					}
			}

			//show page-relation?
			if($this->config['show_page'] && !empty($page))
			{
				$output .= ' (page)';
			}

			//iterate all children, if present
			if(isset($val['children'][0]))
			{
				$output .= $this->list_element_links($name, $val['children'], $level+1);
				$output .= $li_tabs . "</li>";
			}
			else
			{
				$output .= "</li>";
			}
			unset($class, $url);
		}
		$output .= $tabs . "</ul>";
		return $output;
	}

	//creates new nav-container
	function create($name, $options = array())
	{
		$slug = (isset($options['slug']))
			? $options['slug']
			: Inflector::slug($name);

		$items = (isset($options['items']))
			? $options['items']
			: array();

		//TODO: refactor
		if(!array_key_exists('name', $options)) $options['name'] = $name;
		if(!array_key_exists('slug', $options)) $options['slug'] = $slug;
		if(!array_key_exists('cssclass', $options)) $options['cssclass'] = '';

		if(array_key_exists('items', $options)) unset($options['items']);

		$this->cNavis[$slug] = $options;
		$this->cItems[$slug] = $items;
		return $slug;
	}

	//adds to specified nav-container
	function add($name, $item = array(), $url = array(), $options = array())
	{
		if(!isset($this->cNavis[$name]))
		{
			$name = $this->create($name);
		}

		if(is_string($item) && isset($url))
		{
			$temp_item = array();
			$temp_item['name'] = $item;
			$temp_item['url'] = $url;
			$item = array_merge($temp_item, $options);
		}
		$data = array();
		#$data[$this->navModel] = $this->cNavis[$name];
		$data[$this->itemModel] = $item;
		$data['children'] = array();

		if(!array_key_exists('cssclass', $data[$this->itemModel])) $data[$this->itemModel]['cssclass'] = '';

		if(array_key_exists('children', $item))
		{
			$data['children'] = $item['children'];
			unset($item['children']);
		}
		array_push($this->cItems[$name], $data);
		return $data;
	}

	function _init()
	{
		//first, check if we run with database
		if(false && file_exists(CONFIGS.'database.php')) //TODO: check for active connection.
		{
			uses('model' . DS . 'connection_manager'); //TODO: check, if we need this line
			$db = ConnectionManager::getInstance();
			$connected = $db->getDataSource('default');

			if ($connected->isConnected())
			{
				if (!class_exists($this->navModel))
				{
					App::import('Model', $this->navModel);
					if(!class_exists($this->navModel)) App::import('Model', $this->pluginName.'.'.$this->navModel); //no Model there, must be plugin-use
				}
				if(!$this->navObject)
				{
					$this->navObject = new $this->navModel();
				}
			}
		}
		//debug($this->navObject);
	}

}
?>