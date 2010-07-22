<?php
/**
 * WidgetsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class WidgetsController extends AppController
{

	var $paginate = array(
		'Widget' => array(
			'limit' => 100,
		)
	);

	function admin_get()
	{
		if(isset($this->passedArgs['id']))
		{
			//find that one
		} elseif(isset($this->passedArgs['slug'])) {
			//find that one
		} elseif(isset($this->passedArgs['type'])) {
			//build that type
		}
		//TODO: hm?
		// debug($this->passedArgs);
		$this->set($this->passedArgs);
		$this->render('/elements/widget', 'ajax');
	}


/**
 * lists all available lotteries
 *
 * @access public
 */
	function admin_index()
	{
		$search = (isset($this->params['named']['search']))
			? $this->params['named']['search']
			: '';
			
		$conditions = array();
		
		if(!empty($search)) {
			$conditions['OR'] = array(
				'Widget.id =' => $search,
				'Widget.name LIKE' => '%'.$search.'%',
				'Widget.title LIKE' => '%'.$search.'%',
				'Widget.body LIKE' => '%'.$search.'%',
				'Widget.slug LIKE' => '%'.$search.'%',
			);
		}

		$contain = array();

		$this->set('search', $search);
		
		$this->data = $this->paginate('Widget', $conditions);
	}

/**
 * View action
 *
 * @access public
 * @param integer $id ID of record
 */	
	function admin_view($id = null)
	{
		if (!$id)
		{
			return $this->Flash->error(
				__('Invalid Widget.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Widget->read(null, $id);
	}

/**
 * add action
 *
 * @access public
 */	
	function admin_add()
	{
		if(!empty($this->data))
		{
			$this->Widget->create($this->data);
			if($this->Widget->validates())
			{
				if($this->Widget->save($this->data, false))
				{
					$id = $this->Widget->getInsertId();
					$this->Flash->success(
						__('Widget :Widget.name saved.', true),
						array('action' => 'edit', $id)
					);
				} else {
					return $this->Flash->error(
						__('Widget :Widget.name could not be saved.', true)
					);
				}
			}
		}
	}

/**
 * edit action
 *
 * @access public
 * @param integer $id ID of record
 */	
	function admin_edit($id = null)
	{
		if (!$id)
		{
			return $this->Flash->error(
				__('Invalid Widget.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$this->Widget->create($this->data);
			if($this->Widget->validates())
			{
				if($this->Widget->save($this->data, false))
				{
					$this->Flash->success(
						__('Widget :Widget.name saved.', true),
						array('action' => 'edit', $this->data['Widget']['id'])
					);
				} else {
					$this->Flash->error(
						__('Widget :Widget.name could not be saved.', true)
					);
				}
			}
		}
		$this->data = $this->Widget->read(null, $id);
	}

/**
 * View action
 *
 * @access public
 * @param integer $id ID of record
 */	
	function admin_delete($id = null)
	{
		if (!$id)
		{
			$this->Flash->error(
				__('Invalid Widget.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Widget->read(null, $id);
		$result = $this->Widget->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('Widget :Widget.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Flash->success(
			__('Widget :Widget.name successfully deleted.', true),
			$this->referer(array('action'=>'index'))
		);
	}

}

?>