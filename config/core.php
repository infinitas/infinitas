<?php
	/**
	 * This is core configuration file.
	 *
	 * Use it to configure core behavior of Cake.
	 *
	 * PHP versions 4 and 5
	 *
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
	 * @link http://cakephp.org CakePHP(tm) Project
	 * @package cake
	 * @subpackage cake.app.config
	 * @since CakePHP(tm) v 0.2.9
	 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */
	define('LOG_ERROR', 2);




	//no home
	Configure::write('Rating.require_auth', true);
	Configure::write('Rating.time_limit', '4 weeks');

	Configure::write('Reviews.auto_moderate', true);

	$App = App::getInstance();
	$App::build();
	$App::build(
		array(
			'plugins' => array(
				APP . 'infinitas' . DS,
				APP . 'extensions' . DS,
				APP . 'infinitas' . DS . 'shop'. DS .'plugins' . DS
			)
		),
		false
	);
	
	App::import('Libs', 'Events.Events');
	EventCore::getInstance();

	EventCore::trigger(new StdClass(), 'setupConfig');