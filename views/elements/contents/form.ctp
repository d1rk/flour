<?php

//instead of fieldsets, we may use an accordeon here.
echo $this->Html->tag('fieldset');

	echo $this->Html->tag('legend', __('Naming', true));

	echo $this->Form->input('Lottery.name');
	echo $this->Form->input('Lottery.slug');

echo $this->Html->tag('/fieldset');

?>