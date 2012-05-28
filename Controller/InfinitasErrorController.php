<?php
	App::uses('CakeErrorController', 'Controller');
	
	class InfinitasErrorController extends CakeErrorController {
		public $components = array(
			'Session',
			'Auth',
			'Libs.Infinitas',
			'Events.Event',
			'Themes.Themes',
			'Modules.ModuleLoader'
		);
		
		public $uses = array();
	}