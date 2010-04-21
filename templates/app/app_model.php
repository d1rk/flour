<?php
/**
 * AppModel
 *
 * @package default
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com
 **/
class AppModel extends Model
{
	public $actsAs = array(
		'Containable',
	);

	function beforeSave($options = null)
	{
		$user_id = Authsome::get('User.id');
		if(isset($this->data[$this->name]['slug']) && $this->Behaviors->attached('Tree'))
		{
			$path = array();
			if(!empty($this->data[$this->name]['parent_id']))
			{
				$path = $this->getPath($this->data[$this->name]['parent_id'], array('type', 'slug')); //get full path from parent
				$path = Set::extract('/'.$this->name.'[type='.$this->data[$this->name]['type'].']/slug', $path); //extract all slugs (from types identical to current)
			}
			$path[] = $this->data[$this->name]['slug']; //append current one
			$this->data[$this->name]['fullslug'] = join('/', $path); //glue together with '/'
		}
		if(empty($this->id) && empty($this->data[$this->name]['id']))
		{
			$this->data[$this->name]['created_by'] = $user_id;
		} else {
			$this->data[$this->name]['modified_by'] = $user_id;
		}
		return parent::beforeSave($options);
	}

	function afterSave($created)
	{
		if($created) {
			$this->data[$this->alias]['id'] = $this->getLastInsertId();
		}
		return parent::afterSave($created);
	}

}
?>