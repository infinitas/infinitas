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
		/**
		 * place holder for the mustache templating engine.
		 */
		public $Mustache = null;

		/**
		 * internal cache of template parts from the entire system
		 */
		private $__mustacheTemplates = array();

		/**
		 * internal cache of vars that are used in the mustache template rendering
		 */
		private $__vars = array();

		/**
		 * get the
		 */
		public function __construct(&$controller, $register = true) {
			$this->Mustache = new Mustache();
			parent::__construct(&$controller, $register);
		}

		/**
		 * render views
		 *
		 * Lets cake do its thing, then takes the output and runs it through
		 * mustache, doing all the template rendering things, and then returns
		 * the final output to where ever its going.
		 *
		 * you can pass ?mustache=false in the url to see the raw output skipping
		 * the template rendering. could be handy for debugging. if debug is off
		 * this has no effect.
		 */
		public function _render($___viewFn, $___dataForView, $loadHelpers = true, $cached = false) {
			$out = parent::_render($___viewFn, $___dataForView, $loadHelpers, $cached);
			
			// only on for admin or it renders the stuff in the editor which is pointless
			// could maybe just turn it off for edit or some other work around
			if(!isset($this->params['admin'])){
				$this->params['admin'] = false;
			}
			
			if($this->Mustache !== null && !$this->params['admin']){
				if(empty($this->__mustacheTemplates)){
					$this->__mustacheTemplates = $this->Event->trigger('requireGlobalTemplates');
				}

				$this->__vars['templates'] =& $this->__mustacheTemplates['requireGlobalTemplates'];
				$this->__vars['viewVars']  =& $this->viewVars;
				$this->__vars['params']    =& $this->params;

				if(Configure::read('debug') < 1){
					unset($this->params['url']['mustache']);
				}

				if(!isset($this->params['url']['mustache']) || $this->params['url']['mustache'] != 'false'){
					$out = $this->Mustache->render('{{%UNESCAPED}}{{%DOT-NOTATION}}' . $out, $this->__vars);
				}
			}
			
			return $out;
		}

	}