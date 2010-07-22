<?php
/**
 * Widget Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class Widget extends FlourAppModel
{
	var $actsAs = array(
		'Flour.flexible',
		'Flour.Taggable',
	);

	var $hasMany = array(
		'Flour.WidgetField',
	);

}
?>