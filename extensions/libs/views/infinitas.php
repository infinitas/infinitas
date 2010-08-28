<?php
	/**
	 * Infinitas View
	 *
	 * makes the mustache templating class available in the views, and extends
	 * the Theme View to allow the use of themes.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package infinitas
	 * @subpackage infinitas.extentions.views.infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::import('Lib', 'Libs.Mustache');
	App::import('View', 'Theme');

	class InfinitasView extends ThemeView{
		public $Mustache = null;
		
		function __construct(&$controller, $register = true) {
			$this->Mustache = new Mustache();
			parent::__construct(&$controller, $register);
		}
	}