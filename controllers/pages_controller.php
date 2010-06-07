<?php
/**
 * PagesController
 * 
 * In reality, this is a node-controller
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class PagesController extends AppController
{

/**
 * How Paging will be done
 * see view-file for grid-options
 *
 * @var string
 * @access private
 */
 	var $paginate = array(
 		'Page' => array(
 			'limit' => 50,
 			'order' => array('Page.lft' => 'ASC', 'Page.fullslug' => 'ASC'),
 		)
 	);

/**
 * displays one page by slug or id
 *
 * @param string $slug_or_id This is a fullslug or a id of a page
 * @return NULL
 * @access public
 */
	function index() {
		$this->data = $this->Page->find('all');
		debug($this->data);
	}


/**
 * displays one page by slug or id
 *
 * @param string $slug_or_id This is a fullslug or a id of a page
 * @return NULL
 * @access public
 */
	function view() {
		$slugs = func_get_args(); //get all args
		$slug_or_id = join('/', $slugs); //join them together with / to generate a valid slug
		if (!$slug_or_id) {
			$this->Session->setFlash(String::insert( __('Invalid Page: :page.', true), array('page' => $slug_or_id)));
			$this->redirect('/'); //TODO: this may not work
		}
		$conditions = array();
		$find_key = (preg_match(UUID, $slug_or_id)) ? 'Page.id' : 'Page.fullslug';
		$conditions[$find_key] = $slug_or_id;
		$page = $this->Page->find('first', compact('conditions'));
		if(empty($page))
		{
			if('users' == $this->Session->read('Auth.User.usergroup_slug'))
			{
				$this->action = 'add';
			} else {
				$this->cakeError('404', array('code' => '404', 'name' => __('Page not found', true), 'message' => $this->here));
			}
		}
		Configure::write('cms.current.page', $page); //write that down, so we can use it everywhere
		
		//TODO: check here, if that is all there
		$this->viewPath = 'templates';
		$this->render($page['Page']['template'], $page['Page']['layout']);
	}

/**
 * PagessController::admin_index()
 * Lists all pages with given filters
 *
 * @param string $searchterm Term to search for (can be multiple terms, with + and -)
 * @return NULL
 * @access public
 */
	function admin_index()
	{
		$conditions = array();
/*		$conditions = $this->_buildSearchConditions('Page', array(
				'Page.id',
				'Page.fullslug',
				'Page.name',
				'Page.title',
				'Page.body',
				'Page.excerpt',
				'Page.description',
				'Creator.username',
			));
*/		$this->data = $this->paginate('Page', $conditions);
		$parents = $this->Page->generatetreelist(array(), '{n}.Page.id', '{n}.Page.name', ' - ');
		$this->set(compact('parents'));
	}

/**
 * PagessController::admin_view()
 * displays one page by id
 *
 * @param string $id This is the unique id of a page
 * @return NULL
 * @access public
 */
	function admin_view($id) {
		$conditions = array('Page.id' => $id);
		$page = $this->Page->find('first', compact('conditions'));
		if(empty($page))
		{
			if('users' == $this->Session->read('Auth.User.usergroup_slug'))
			{
				$this->action = 'add';
			} else {
				$this->cakeError('404', array('code' => '404', 'name' => __('Page not found', true), 'message' => $this->here));
			}
		}
		Configure::write('cms.current.page', $page); //write that down, so we can use it everywhere
		
		//TODO: check here, if that is all there
		$this->viewPath = 'templates';


		//$this->theme = 'bruensicke';
		$this->autoRender = 0;
		$this->autoLayout = 1;
		//debug($this);
		
		$output = $this->render($page['Page']['template'], $page['Page']['layout']);
		$this->set('output', $this->output);
	}


/**
 * PagessController::admin_add()
 * To add a new page
 *
 * @return NULL
 * @access public
 */
	function admin_add()
	{
		if(!empty($this->data))
		{
			$this->Page->create($this->data);
			if($this->Page->validates())
			{
				if($data = $this->Page->save())
				{
					$this->Flash->success(
						__('Your page <strong>:Page.name</strong> has been saved.', true), 
						array('action' => 'index')
					);
				}
			}
		}
		$parents = $this->Page->generatetreelist(array(), '{n}.Page.id', '{n}.Page.name', ' - ');
		$this->set(compact('parents'));
	}

/**
 * PagessController::admin_edit()
 * To edit an existing page
 *
 * @return NULL
 * @access public
 */
	function admin_edit($id)
	{
		if(!empty($this->data))
		{
			$this->Page->create($this->data);
			if($this->Page->validates())
			{
				if($data = $this->Page->save())
				{
					$this->Session->setFlash(String::insert( __('Your page <strong>:page</strong> has been saved.', true), array('page' => $this->data['Page']['name'])), 'flash_success');
					$this->redirect(array('action' => 'index'));
					//$this->redirect('/'.$data['Page']['fullslug']);
				}
			}
		} else {
			$this->data = $this->Page->read(null, $id);
		}
		$parents = $this->Page->generatetreelist(array('Page.id !=' => $id), '{n}.Page.id', '{n}.Page.name', ' - ');
		$this->set(compact('parents'));
	}

	function admin_recover()
	{
		$this->Page->recover();
		$this->Page->Site->_cache();
		$this->redirect(array('action' => 'index'));
	}


}
?>
