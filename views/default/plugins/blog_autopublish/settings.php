<?php 

if (!isset($vars['entity']->users)) {
	$vars['entity']->users = 'admin';
}

echo '<div>';
echo elgg_echo('blog:publish_on:create');
echo ' ';
echo elgg_view('input/select', array(
	'name' => 'params[users]',
	'options_values' => array(
		'all' => elgg_echo('LOGGED_IN'),
		'admin' => elgg_echo('admin')
	),
	'value' => $vars['entity']->users,
));
echo '</div>';

if (!isset($vars['entity']->notify_author)) {
	$vars['entity']->notify_author = 'yes';
}
echo '<div>';
echo elgg_echo('blog:publish_on:notify');
echo ' ';
echo elgg_view('input/select', array(
	'name' => 'params[notify_author]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')
	),
	'value' => $vars['entity']->notify_author,
));
echo '</div>';

if (!isset($vars['entity']->input_field)) {
	$vars['entity']->input_field = 'datepicker';
}
echo '<div>';
echo elgg_echo('blog:publish_on:input_field');
echo ' ';
echo elgg_view('input/select', array(
	'name' => 'params[input_field]',
	'options_values' => array(
		'datepicker' => elgg_echo('blog:publish_on:option:datepicker'),
		'hourpicker' => elgg_echo('blog:publish_on:option:hourpicker')
	),
	'value' => $vars['entity']->input_field,
));
echo '</div>';