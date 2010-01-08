<?php
/**
* Comment Template.
*
* @todo Implement .this needs to be sorted out.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://www.dogmatic.co.za
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class CoreConfigComponent extends Object {
	function initialize(&$controller) {
		$this->Controller = $controller;

		$this->setupConfig();
		$this->setupTheme();
	}

	function setupConfig(){
		$configs = ClassRegistry::init('Management.Config')->getConfig();

		foreach($configs as $config) {
			Configure::write($config['Config']['key'], $config['Config']['value']);
		}
	}

	function setupTheme(){
		//currently there is no theme being used from db, since that needed changes in the db
		//the fallback is in use now, uncomment this line below to use infinitas default layout(aqueous theme needs some more work)
		$this->Controller->theme = 'aqueous';
		
		//the infinitas theme can be removed later, it currently still is there as placeholder
		//any work on the layout/css/img for the default theme should be done in the app/views/layouts and app/webroot
		//the default layout and css is not in themed because of fallback in case incorrect theme has been selected, or the uploaded theme is corrupt
		//also this setting of theme can be removed when the theme work is done
		//this is just the first setup
		//do not change below
		$this->Controller->layout = 'front';
		$conditions = array('Theme.admin' => 0, 'Theme.active' => 1);
		if ( isset( $this->Controller->params['admin'] ) && $this->Controller->params['admin'] ){
			$conditions['Theme.admin'] = 1;
			$this->Controller->layout = 'admin';
		}
		$theme = ClassRegistry::init('Theme.Theme')->getCurrnetTheme(array());
		if (!isset($theme['Theme']['name'])) {
			$theme['Theme'] = array();
		} else {
			//if the db values are being updated this line can be uncommented.
			//$this->Controller->theme = $theme['Theme']['name'];
		}
		Configure::write('Theme',$theme['Theme']);
	}
}

?>