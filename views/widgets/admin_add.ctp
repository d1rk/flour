<?php
$this->title = __('Widgets', true);
$this->description = __('Add new Widgets.', true);

$this->addCrumb(
	__('Widgets', true),
	array('controller' => 'widgets', 'action' => 'index')
);

$this->addCrumb(
	__('add', true),
	array('controller' => 'widgets', 'action' => 'add')
);

$this->Nav->add('Primary', array(
	'name' => __('cancel', true),
	'url' => array('controller' => 'widgets', 'action' => 'index'),
	'type' => 'link',
	'ico' => 'cross',
	'confirm' => __('Are you sure you want to cancel?', true),
));

$this->Nav->add('Primary', array(
	'name' => __('save', true),
	'type' => 'button',
	'ico' => 'disk',
	'class' => 'positive',
));

//retrieve the widget to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: 'example';


echo $this->Form->create('Widget', array('action' => $this->action));
echo $this->element('content_start');
	echo $this->Grid->open();

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			//TODO: use widgetFactory Form
			echo $this->element('box', array(
				'caption' => __('Enter Widget Details.', true),
				'content' => $this->element('widget', array('type' => $type, 'template' => 'admin')),
				'class' => 'panel widget_content',
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('box', array(
				'caption' => __('Control Widget', true),
				'content' => $this->element('widgets/form', array('type' => $type)),
				'class' => 'panel',
			));

		echo $this->Html->tag('/div'); //div.span-10 last

	echo $this->Grid->close();
echo $this->element('content_stop');
echo $form->end();

?>
<script type="text/javascript">
$().ready(function()
{
	$('#WidgetTypeSelect').change(function() {
		var widgetType = $(this).attr('value');
		if(widgetType=='') {
			$('div.widget_content').html('');
		} else {
			$.get(
				"<?php echo Router::url(array('controller' => 'widgets', 'action' => 'get')); ?>/type:"+widgetType+"/template:admin",
				function(data) {
					$('div.widget_content').html(data);
				}
			);
		}
	});
});
</script>