<div id="modal" class="jqmWindow"></div>
<script>
	var t = $('#modal');
	var load=function(h)
	{

	};
	var hide=function(h){
		h.o.remove();
		h.w.fadeOut(200);
		t.html('Please Wait...');
	};
	$().ready(function(){
		$("a.help").bind("click", function(){ $("div#help").slideToggle("fast")});
		$('#modal').jqm({trigger: $("a[class*=modal], li.modal a"), ajax: '@href', target: t, modal: false, onLoad: load, onHide: hide, overlay: 60}); //TODO: change to class modal, not link
	});
</script>
