<?php
/**
 * CustomListItem Model
 * 
 * An Item in a CustomList
 *
 * @package default
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com
 **/
class CustomListItem extends AppModel
{
	var $tablePrefix = 'flour_';

	var $belongsTo = array('CustomList');
}
?>