<?php
	$config['Contents'] = array(
		'truncate_category' => 100,
		 'slugUrl' => array(
			 'category' => array(
				 'GlobalCategory.id' => 'id',
				 'GlobalCategory.slug' => 'slug',
				 'url' => array(
					 'plugin' => 'contents',
					 'controller' => 'global_categories',
					 'action' => 'view'
				 )
			 ),
		 )
	);