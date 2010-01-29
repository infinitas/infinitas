<?php
/**
 * Api Generator Plugin App Controller
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiGeneratorAppController extends AppController {
/**
 * theme
 *
 **/
	public $theme = 'api';
/**
 * view
 *
 **/
	public $view = 'Theme';
/**
 * beforeFilter callback
 *
 * @return void
 **/
	public function beforeFilter() {
		parent::beforeFilter();
		$this->ApiConfig = ClassRegistry::init('ApiGenerator.ApiConfig');
		$this->ApiConfig->read();
		$path = $this->ApiConfig->getPath();
		if (empty($path)) {
			$path = APP;
			$this->ApiConfig->data['paths'][$path] = true;
		}
		$this->path = Folder::slashTerm(realpath($path));
	}
/**
 * Error Generating Page.
 * Needs to be public for Security Blackhole.
 *
 * @return void
 **/
	public function _notFound($name = null, $message = null) {
		$name = ($name) ? $name : 'Page Not Found';
		$message = ($message) ? $message : $this->params['url']['url'];
		$this->cakeError('error', array(
			'name' => $name,
			'message' => $message,
			'code' => 404,
			'url' => $this->params['url']['url']
		));
		$this->_stop();
	}
}
?>
