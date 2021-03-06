<?php
/**
 * Edit blog form
 *
 * @package Blog
 */

$blog = get_entity($vars['guid']);
$vars['entity'] = $blog;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="mbm elgg-text-help">' . $draft_warning . '</span>';
}

$action_buttons = '';
$delete_link = '';
$preview_button = '';

if ($vars['guid']) {
	// add a delete button if editing
	$delete_url = "action/blog/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/url', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt',
		'confirm' => true,
	));
}

// published blogs do not get the preview button
if (!$vars['guid'] || ($blog && $blog->status != 'published')) {
	$preview_button = elgg_view('input/submit', array(
		'value' => elgg_echo('preview'),
		'name' => 'preview',
		'class' => 'elgg-button-submit mls',
	));
}

// Autopublish options
$publish_on_label = $publish_on_input = $publish_on_div = "";
$show_autopublish = false;
$users = elgg_get_plugin_setting('users', 'blog_autopublish', 'admin');
if($users == 'admin'){
	if(elgg_is_admin_logged_in()){
		$show_autopublish = true;
	}
} else {
	$show_autopublish = true;
}	
if($show_autopublish){
	elgg_require_js('blog/autopublish');
	$publish_on_label = elgg_echo('blog:publish_on');
	$publish_on_value = $blog->publish_on ?: $vars['publish_on'];
	$input_field = elgg_get_plugin_setting('input_field', 'blog_autopublish', 'datepicker');
	if($input_field == 'datepicker'){
		$publish_on_input = elgg_view('input/date', array('value' => $publish_on_value, 'name' => 'publish_on', 'timestamp' => true, 'class' => '',));
	} else {
		$publish_on_year = $publish_on_month = $publish_on_date = $publish_on_hour = $publish_on_minute = "";
		$days = $months =  $years = $hours = $minutes = array();
		if(!empty($publish_on_value)){
			$date = date('j-n-Y-G-i', $publish_on_value);
			$explode = explode('-', $date);
			$publish_on_date = $explode[0];
			$publish_on_month = $explode[1];
			$publish_on_year = $explode[2];
			$publish_on_hour = $explode[3];
		}
		for ($i=1; $i<=31; $i++) {
			$v = str_pad($i, 2, "0", STR_PAD_LEFT);
			$days[$v] = $v;
		}
		$publish_on_date_input = elgg_view('input/select', 	array('value' => $publish_on_date, 	'name' => "publish_on['j']", 	'options_values' => $days, 	'class' => 'mrm'));

		for ($i=1; $i<=12; $i++) {
			$v = str_pad($i, 2, "0", STR_PAD_LEFT);
			$months[$v] = $v;
		}
		$publish_on_month_input = elgg_view('input/select', array('value' => $publish_on_month, 'name' => "publish_on['n']", 	'options_values' => $months,'class' => 'mrm'));

		$this_year = date('Y');
		for ($i=$this_year; $i<=($this_year+10); $i++) {
			$years[$i] = $i;
		}
		$publish_on_year_input = elgg_view('input/select', 	array('value' => $publish_on_year, 	'name' => "publish_on['Y']", 	'options_values' => $years,	'class' => 'mrm'));

		for ($i=0; $i<=23; $i++) {
			$v = str_pad($i, 2, "0", STR_PAD_LEFT);
			$hours[$v] = "$v:00";
		}
		$publish_on_hour_input = elgg_view('input/select', 	array('value' => $publish_on_hour, 	'name' => "publish_on['G']", 	'options_values' => $hours, 'class' => 'mrm'));
		
		$publish_on_minute_input = elgg_view('input/hidden',array('value' => "00", 'name' => "publish_on['i']", 'class' => ''));

		$publish_on_input = $publish_on_date_input . $publish_on_month_input . $publish_on_year_input . $publish_on_hour_input . $publish_on_minute_input;
	}	
	$publish_on_div = "	<div class='publish_on hidden'>
							<label for='publish_on'>$publish_on_label</label>
							$publish_on_input
						</div>";
}						
// End Autopublish					

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
$action_buttons = $save_button . $preview_button . $delete_link;

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'id' => 'blog_title',
	'value' => $vars['title']
));

$excerpt_label = elgg_echo('blog:excerpt');
$excerpt_input = elgg_view('input/text', array(
	'name' => 'excerpt',
	'id' => 'blog_excerpt',
	'value' => elgg_html_decode($vars['excerpt'])
));

$body_label = elgg_echo('blog:body');
$body_input = elgg_view('input/longtext', array(
	'name' => 'description',
	'id' => 'blog_description',
	'value' => $vars['description']
));

$save_status = elgg_echo('blog:save_status');
if ($vars['guid']) {
	$entity = get_entity($vars['guid']);
	$saved = date('F j, Y @ H:i', $entity->time_created);
} else {
	$saved = elgg_echo('never');
}

$status_label = elgg_echo('status');
$status_input = elgg_view('input/select', array(
	'name' => 'status',
	'id' => 'blog_status',
	'value' => $vars['status'],
	'options_values' => array(
		'draft' => elgg_echo('status:draft'),
		'published' => elgg_echo('status:published')
	)
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/select', array(
	'name' => 'comments_on',
	'id' => 'blog_comments_on',
	'value' => $vars['comments_on'],
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'name' => 'tags',
	'id' => 'blog_tags',
	'value' => $vars['tags']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'id' => 'blog_access_id',
	'value' => $vars['access_id'],
	'entity' => $vars['entity'],
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
));

$categories_input = elgg_view('input/categories', $vars);

// hidden inputs
$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));


echo <<<___HTML

$draft_warning

<div>
	<label for="blog_title">$title_label</label>
	$title_input
</div>

<div>
	<label for="blog_excerpt">$excerpt_label</label>
	$excerpt_input
</div>

<div>
	<label for="blog_description">$body_label</label>
	$body_input
</div>

<div>
	<label for="blog_tags">$tags_label</label>
	$tags_input
</div>

$categories_input

<div>
	<label for="blog_comments_on">$comments_label</label>
	$comments_input
</div>

<div>
	<label for="blog_access_id">$access_label</label>
	$access_input
</div>

<div>
	<label for="blog_status">$status_label</label>
	$status_input
</div>

$publish_on_div

<div class="elgg-foot">
	<div class="elgg-subtext mbm">
	$save_status <span class="blog-save-status-time">$saved</span>
	</div>

	$guid_input
	$container_guid_input

	$action_buttons
</div>

___HTML;
