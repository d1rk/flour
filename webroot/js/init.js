$().ready(function(){

	//make switches on divs with click on _switch #ids
	$('input[id*=_switch]').bind('click', function(){ var target = $(this).attr('id').split('_switch').join(''); $('div.'+target).slideToggle('fast'); } );
	//make switches on dropdowns
	$('select[id*=_switch]').bind('change', function(){ var target = $(this).attr('id').split('_switch').join(''); if(this.value == ''){ $('div.'+target).slideUp('fast'); } else { $('div.'+target).slideDown('fast'); } } );

	//tipsy on all elements with title attribut set
	$('*[title]').tipsy();

	$("tr, div.items div.item").hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});
	$("tr th a[href*=direction:asc]").addClass("asc");
	$("tr th a[href*=direction:desc]").addClass("desc");

	$("div.actions, div.actions div.submit").hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});
	$("input, textarea").focus(function(){$(this).addClass("active");});$("input, textarea").blur(function(){$(this).removeClass("active");});
	$("div.input input, div.input select, div.input textarea").focus(function(){$(this).parent().addClass("active");});$("div.input input, div.input select, div.input textarea").blur(function(){$(this).parent().removeClass("active");});

	//does AUTO-Enter on elements that have .btnEnter
	//$("form input").keypress(function (e) { if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) { $('.btnEnter').click(); return false; } else { return true; } });
	//$("img[src$=.png]").ifixpng();
});
