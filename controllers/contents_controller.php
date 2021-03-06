<?php
/**
 * ContentsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class ContentsController extends AppController
{

	var $paginate = array(
		'Content' => array(
			'limit' => 100,
		)
	);

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
				'Content.id =' => $search,
				'Content.name LIKE' => '%'.$search.'%',
				'Content.title LIKE' => '%'.$search.'%',
				'Content.body LIKE' => '%'.$search.'%',
				'Content.slug LIKE' => '%'.$search.'%',
			);
		}

		$contain = array();

		$this->set('search', $search);
		
		$this->data = $this->paginate('Content', $conditions);
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
				__('Invalid Content.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Content->read(null, $id);
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
			$this->Content->create($this->data);
			if($this->Content->validates())
			{
				if($this->Content->save($this->data, false))
				{
					$id = $this->Content->getInsertId();
					$this->Flash->success(
						__('Content :Content.name saved.', true),
						array('action' => 'edit', $id)
					);
				} else {
					return $this->Flash->error(
						__('Content :Content.name could not be saved.', true)
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
				__('Invalid Content.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$this->Content->create($this->data);
			if($this->Content->validates())
			{
				if($this->Content->save($this->data, false))
				{
					$this->Flash->success(
						__('Content :Content.name saved.', true),
						array('action' => 'edit', $this->data['Content']['id'])
					);
				} else {
					$this->Flash->error(
						__('Content :Content.name could not be saved.', true)
					);
				}
			}
		}
		$this->data = $this->Content->read(null, $id);
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
				__('Invalid Content.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Content->read(null, $id);
		$result = $this->Content->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('Content :Content.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Flash->success(
			__('Content :Content.name successfully deleted.', true),
			$this->referer(array('action'=>'index'))
		);
	}


}

?>