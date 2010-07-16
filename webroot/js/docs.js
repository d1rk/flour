$().ready(function() {
	
	// add class 'last' to last menu elements
	$('#nav ul').find('li:last').addClass('last');
	
	// submenu width fix
	// makes the submenu as wide (or wider) as the parent menu entry
	// also fixes a firefox 2 bug
	$('#nav ul ul li').each(function() {
		$(this).css('width', Math.max($(this).parent().width(), $(this).parent().parent().width())-2);
	})
	
	// animate submenu
	$('#nav ul ul').hide();
	$('#nav ul li').hover(
		function() { $(this).find('ul').slideDown(100); },
		function() { $(this).find('ul').slideUp(100);}
	);
	
})