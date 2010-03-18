<?php
echo $form->input('User.name', array(
	'label' => 'Name', 
	'error' => array(
		'isUnique' => __('Username is already taken', true),
	),
));
if($this->action == 'register')
{
	echo $form->input('User.email', array(
		'label' => 'Email', 
		'error' => array(
			'isUnique' => __('Email is already registered', true),
			'isEmail' => __('Please enter a valid email-address', true),
		)
		));
		echo $form->input('User.password', array('label' => "Password"));

} else {
	echo $form->input('User.password', array('label' => "Password"));
	echo $form->input('User.remember', array(
		'label' => __("Remember me for 2 weeks", true),
		'type' => "checkbox"
	));
}
?>