<?php

elgg_register_event_handler('init', 'system', 'blog_autopublish_init');

function blog_autopublish_init() {		
	elgg_unregister_action('blog/save');
	elgg_register_action('blog/save', __DIR__ . "/actions/blog/save.php");
	
	//Check whether hourly cronjob is running properly
	if(elgg_is_admin_logged_in()){
		$lastRun = elgg_get_site_entity()->getPrivateSetting("cron_latest:hourly:ts");
		if((time() - $lastRun) > 3600){
			elgg_add_admin_notice('blog_autopublish_cron_failure', elgg_echo('blog:publish_on:cronfailure'));
		}		
	}	
	elgg_register_plugin_hook_handler('cron', 'hourly', 'blog_autopublish_cron');		
}

function blog_autopublish_cron($hook, $entity_type, $returnvalue, $params) {
	$resulttext = elgg_echo('No blogs to publish');
	// Get all blogs scheduled with in next one hour and publish them
	$ia = elgg_set_ignore_access(true);
	$offset = time() + 3600;
	$blogs = elgg_get_entities_from_metadata(array(
												'type' => 'object',
												'subtype' => 'blog',
												'metadata_name_value_pairs' => array('name' => 'publish_on', 'value' => $offset, 'operand' => '<='),
											));	
	if($blogs){
		$site = elgg_get_site_entity();
		foreach($blogs as $blog){		
			$author_guid =  $blog->owner_guid;
			$author = get_user($author_guid);
			elgg_create_river_item(array(
				'view' => 'river/object/blog/create',
				'action_type' => 'create',
				'subject_guid' => $author_guid,
				'object_guid' => $blog->getGUID(),
			));			
			elgg_trigger_event('publish', 'object', $blog);
			$blog->status = 'published';
			$blog->access_id = ACCESS_PUBLIC;
			$blog->time_created = time();
			$blog->deleteMetadata('publish_on');
			$blog->save();
			$notify_author = elgg_get_plugin_setting('notify_author', 'blog_autopublish', 'yes');
			if($notify_author){
				$message_subject = elgg_echo('blog:publish_on:message:subject', array(), $author->language);
				$message_body = elgg_echo('blog:publish_on:message:body', array($author->name, $blog->title, $site->name, $blogg->getULR()), $author->language);
				notify_user($author_guid, $site->guid, $message_subject, $message_body, array(), 'email');
			}
		}
		$resulttext = elgg_echo("blog:publish_on:numbers", array(count($blogs)));
	}	
	elgg_set_ignore_access($ia);
	return $returnvalue . $resulttext;
}
