<?php
/**
 * CustomListItem Model
 * 
 * An Item in a CustomList
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class CustomListItem extends AppModel
{
	var $tablePrefix = 'flour_';

	var $belongsTo = array('CustomList');
}
?>