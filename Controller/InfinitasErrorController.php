<?php
/**
 * InfinitasErrorController
 *
 * @package Infinitas.App.Controller
 */

App::uses('CakeErrorController', 'Controller');

/**
 * InfinitasErrorController
 *
 * @package Infinitas.App.Controller
 */
class InfinitasErrorController extends CakeErrorController {
/**
 * Components to load
 *
 * @var array
 */
	public $components = array(
		'Session',
		'Auth',
		'Libs.Infinitas',
		'Events.Event',
		'Themes.Themes'
	);

/**
 * Disable model loading
 *
 * @var boolean
 */
	public $uses = false;

/**
 * BeforeRender callback
 *
 * Set the error systemt to use the error layout for admin / frontend
 */
	public function beforeRender() {
		$this->layout = 'error';
		if(isset($this->request->params['admin']) && $this->request->params['admin']) {
			$this->layout = 'admin_error';
		}

		$this->viewClass = 'Libs.Infinitas';
	}

}