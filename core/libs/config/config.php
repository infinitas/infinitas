<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	Configure::write('App.encoding', 'UTF-8');

	$config['Routing'] = array(
		'prefixes' => array('admin')
	);

	/**
	 * Session Configuration
	 */
	$config['Session'] = array(
		'save' => 'database',
		'model' => 'Session',
		'table' => 'sessions',
		'database' => 'default',
		'cookie' => 'CAKEPHP',
		'timeout' => '120',
		'start' => true,
		'checkAgent' => true
	);

	/**
	 * Security Configuration
	 */
	$config['Security'] = array(
		'level' => 'medium',
		'salt' => 'dev-DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi',
		'login_attempts' => 3
	);

	/**
	 * Acl Configuration options
	 */	
	$config['Acl'] = array(
		'classname' => 'DbAcl',
		'database' => 'default'
	);

	/**
	 * Core configuration
	 */
	$config['CORE'] = array(
		'active_options' => array('' => 'Please Select', 0 => 'Inactive', 1 => 'Active'),
		'core_options'   => array('' => 'Please Select', 0 => 'Extention', 1 => 'Core',)
	);

	$config['Website'] = array(
		'name' => 'Infinitas Cms',
		'description' => 'Infinitas Cms is a open source content management system that is designed to be fast and user friendly, with all the features you need.',
		'admin_quick_post' => 'blog',
		'allow_login' => true,
		'allow_registration' => true,
		'blacklist_keywords' => 'levitra,viagra,casino,sex,loan,finance,slots,debt,free,interesting,sorry,cool',
		'blacklist_words' => '.html,.info,?,&,.de,.pl,.cn',
		'email_validation' => true,
		'empty_select' => 'Please Select',
		'password_regex' => '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}$',
		'password_validation' => 'Please enter a password with one lower case letter, one upper case letter, one digit, 6-13 length, and no spaces',
		'read_more' => 'Read more...'
	);

	/**
	 * Currency Configuration
	 */
	$config['Currency'] = array(
		'code' => 'USD',
		'name' => 'Dollars',
		'unit' => '$'
	);

	/**
	 * Language Configuration
	 */
	$config['Language'] = array(
		'code' => 'En',
		'name' => 'English',
		'available' => array(
			'En' => 'English'
		)
	);

	/**
	 * Wysiwyg editor Configuration
	 */
	$config['Wysiwyg'] = array(
		'editor' => 'text'
	);

	/**
	 * pagination Configuration
	 */
	$config['Pagination'] = array(
		'nothing_found_message' => 'Nothing was found, try a more generic search.'
	);
	
	// merge to pagination
	Configure::write('Global.pagination_limit', 100);
	Configure::write('Global.pagination_select', '5,10,20,50,100');