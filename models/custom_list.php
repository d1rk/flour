<?php
/**
 * CustomList Model
 * 
 * A Container for CustomlistItems
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class CustomList extends FlourAppModel
{
	var $actsAs = 'Flour.Taggable';

#	var $hasMany = array('CustomListItem');
}
?>