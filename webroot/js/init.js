$().ready(function(){

	//make switches on divs with click on _switch #ids
	$('input[id*=_switch]').bind('click', function(){ var target = $(this).attr('id').split('_switch').join(''); $('div.'+target).slideToggle('fast'); } );
	//make switches on dropdowns
	$('select[id*=_switch]').bind('change', function(){ var target = $(this).attr('id').split('_switch').join(''); if(this.value == ''){ $('div.'+target).slideUp('fast'); } else { $('div.'+target).slideDown('fast'); } } );

	//tipsy on all elements with title attribut set and tipsy class
	$('*[title].tipsy').tipsy();

	$("tr, div.items div.item").hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});
	$("tr, div.items div.item").click(function(){$(this).toggleClass("active");});
	$("tr th a[href*=direction:asc]").addClass("asc");
	$("tr th a[href*=direction:desc]").addClass("desc");

	$("input, textarea").focus(function(){$(this).addClass("active");});$("input, textarea").blur(function(){$(this).removeClass("active");});
	$("div.input input, div.input select, div.input textarea").focus(function(){$(this).parent().addClass("active");});$("div.input input, div.input select, div.input textarea").blur(function(){$(this).parent().removeClass("active");});

	$('.ui-state-default')
	.hover(
		function(){ $(this).addClass("ui-state-hover"); },
		function(){ $(this).removeClass("ui-state-hover"); })
	.mousedown(function(){	$(this).addClass("ui-state-active"); })
	.mouseup(function(){ $(this).removeClass("ui-state-active"); });



	//does AUTO-Enter on elements that have .btnEnter
	//$("form input").keypress(function (e) { if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) { $('.btnEnter').click(); return false; } else { return true; } });
	//$("img[src$=.png]").ifixpng();
});
