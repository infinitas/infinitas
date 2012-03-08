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
	Configure::write('Infinitas.version', '0.9b1');

	$config['Routing'] = array(
		'prefixes' => array('admin')
	);


	$cookieName = 'INFINITAS';
	if(substr(env('REQUEST_URI'), 0, 6) == '/admin') {
		$cookieName .= '_ADMIN';
	}

	/**
	 * Session Configuration
	 */
	$config['Session'] = array(
		'save' => 'database',
		'model' => 'Session',
		'table' => 'sessions',
		'database' => 'default',
		'cookie' => $cookieName,
		'timeout' => '120',
		'start' => true,
		'checkAgent' => true
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
		'description' => 'Infinitas Cms is a open source content management system ' .
			'that is designed to be fast and user friendly, with all the features you need.',
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
	
	
	$config['CoreImages']['path'] = 'core/icons/';
	$config['CoreImages']['images'] = array(
		'actions' => array(
			'accept' => 'accept.png',
			'add' => 'add.png',
			'addCategory' => 'addCategory.png',
			'addItem' => '',
			'arrow-down' => 'arrow-down.png',
			'arrow-left' => 'arrow-left.png',
			'arrow-right' => 'arrow-right.png',
			'arrow-up' => 'arrow-up.png',
			'cancel' => 'cancel.png',
			'copy' => 'copy.png',
			'date' => 'date.png',
			'delete' => 'trash.png',
			'download' => 'download.png',
			'edit' => 'edit.png',
			'export' => 'zip.png',
			'favourite' => 'favourite.png',
			'featured' => 'featured.png',
			'filter' => 'filter.png',
			'-locked' => 'locked.png',
			'move' => 'move.png',
			'new-window' => 'new_window.png',
			'order-asc' => 'order-asc.png',
			'order-desc' => 'order-desc.png',
			'preview' => 'preview.png',
			'print' => 'print.png',
			'reload' => 'reload.png',
			'remove' => 'remove.png',
			'save' => 'save.png',
			'search' => 'search.png',
			'send' => 'send.png',
			'sinc' => 'sinc.png',
			'stats' => 'stats.png',
			'toggle' => 'toggle.png',
			'trash' => 'trash.png',
			'unlock' => 'reload.png',
			'unlocked' => 'unlocked.png',
			'unzip' => 'unzip.png',
			'update' => 'update.png',
			'upload' => 'upload.png',
			'view' => 'view.png',
			'zip' => 'zip.png',
			'zoom-in' => 'zoom-in.png',
			'zoom-out' => 'zoom-out.png'
			),
	
		'applications' => array(
			'ai' => '',
			'doc' => 'doc.png',
			'docx' => 'docx.png',
			'dwt' => 'dwt.png',
			'eps' => '',
			'mirc' => 'mirc.png',
			'ods' => 'ooMaths.png',
			'odt' => 'ooWord.png',
			'pdf' => 'pdf.png',
			'ppt' => 'ppt.png',
			'pptx' => 'ppt.png',
			'ps' => 'ps.png',
			'psd' => 'psd.png',
			'rtf' => '',
			'txt' => 'txt.png',
			'xls' => 'sxl.png',
			'xlsx' => 'xlsx.png'
			),
	
		'archives' => array(
			'7z' => '7z.png',
			'cab' => 'cab.png',
			'dll' => 'dll.png',
			'exe' => 'exe.png',
			'msi' => '',
			'rar' => 'win-rar.png',
			'tar' => 'tar.png',
			'zip' => '',
			),
	
		'folders' => array(
			'config' => 'config.png',
			'documents' => 'documents.png',
			'empty' => 'empty.png',
			'images' => 'images.png',
			'mixed' => 'mixed.png',
			'music' => 'music.png',
			'sys-linux' => 'systemLinux.png',
			'sys-win' => 'systemWin.png',
			'video' => 'video.png',
			'web' => 'web.png'
			),
	
		'images' => array(
			'bmp' => 'bmp.png',
			'gif' => 'gif.png',
			'ico' => '',
			'jpe' => 'jpg.png',
			'jpeg' => 'jpg.png',
			'jpg' => 'jpg.png',
			'png' => 'png.png',
			'svg' => 'svg.png',
			'svgz' => '',
			'tga' => 'tga/png',
			'tif' => 'tiff.png',
			'tiff' => 'tiff.png'
			),
	
		'multiMedia' => array(
			'3gp' => '3gp.png',
			'dvi' => 'dvi.png',
			'mov' => 'mov.png',
			'mp2' => 'mp2.png',
			'mp3' => 'mp3.png',
			'mp4' => 'mp4.png',
			'mpeg' => 'mpeg.png',
			'ogg' => 'ogg.png',
			'ra' => 'rm.png',
			'ram' => 'rm.png',
			'rm' => 'rm.png',
			'rmi' => 'rm.png',
			's3m' => 's3m.png',
			'qt' => 'qt.png',
			'vlc' => 'vlc.png',
			'wav' => 'wav.png',
			'wma' => 'wma.png'
			),
	
		'notifications' => array(
			'forbidden' => 'forbidden.png',
			'help' => 'help.png',
			'info' => 'info.png',
			'message' => 'message.png',
			'status' => 'status.png',
			'stop' => 'stop.png',
			'success' => 'success.png',
			'warning' => 'warning.png',
			'loading' => 'loading.gif'
			),
	
		'social' => array(
			'badoo' => '',
			'gmail' => '',
			'google' => '',
			'facebook' => '',
			'icq' => 'icq.png',
			'last-fm' => 'last-fm.png',
			'msn' => 'msn.png',
			'picasa' => 'picasa.png',
			'rss' => 'rss.png',
			'skype' => 'skype.png',
			'twitter' => '',
			'vcf' => 'vcf.png',
			'yahoo' => 'yahoo-messanger.png'
			),
	
		'status' => array(
			'active' => 'active.png',
			'inactive' => 'inactive.png',
			'home' => 'home.png',
			'not-home' => 'not-home.png',
			'locked' => 'locked.png',
			'not-locked' => 'not-locked.png',
			'featured' => 'featured.png',
			'not-featured' => 'not-featured.png',
			),
	
		'unknown' => array(
			'unknown' => 'unknown.png',
			'readme' => 'readme.png',
			'' => 'readme.png'
			),
	
		'weather' => array(
			'clear' => 'clear.png',
			'clear-night' => 'clear-night.png',
			'cloudy' => 'cloudy.png',
			'fog' => 'fog.png',
			'partly-coudy' => 'partly-coudy.png',
			'partly-cloudy-night' => 'partly-cloudy-night.png',
			'showers' => 'showers.png',
			'snow' => 'snow.png',
			'storm' => 'storm.png'
			),
	
		'web' => array(
			'asx' => 'asx.png',
			'css' => 'css.png',
			'flv' => 'flv.png',
			'htm' => 'html.png',
			'html' => 'html.png',
			'java' => 'java.png',
			'js' => 'js.png',
			'json' => '',
			'php' => 'php.png',
			'php3' => 'php.png',
			'php4' => 'php.png',
			'php5' => 'php.png',
			'php6' => 'php.png',
			'py' => 'py.png',
			'sql' => 'sql.png',
			'swf' => 'flv.png',
			'xml' => 'xml.png'
			)
		);