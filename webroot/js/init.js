$().ready(function(){
	function init()
	{
		$("div#flashMessage").slideDown("fast").bind("click", function(){ $("div.flasher").slideToggle("fast")});
		$("tr:nth-child(even)").addClass("even");
		$("tr:nth-child(odd)").addClass("odd");

		$(".odd, .even, div.actions").hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});
		$("div.actions").hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});
		$("input, textarea").focus(function(){$(this).addClass("active");});$("input, textarea").blur(function(){$(this).removeClass("active");});
		$("div.input input, div.input select, div.input textarea").focus(function(){$(this).parent().addClass("active");});$("div.input input, div.input select, div.input textarea").blur(function(){$(this).parent().removeClass("active");});
		
		//TODO: does not work, because of hostname
		$("a[href*="+document.location+"]").addClass('active');


	
		//$("tbody tr").bind('dblclick', function(){ $(this).closest('img[class*=default]').parent().click(); });
		//new, check those
		//$("table.fixhead").fixedHeader({ width: $(this).parent().innerWidth, height: $(this).parent().innerHeight });
	}
	init();
});


