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
		$this->Controller->layout = 'front';

		if ( isset( $this->Controller->params['admin'] ) && $this->Controller->params['admin'] ){
			$this->Controller->layout = 'admin';
		}

		$theme = ClassRegistry::init('Theme.Theme')->getCurrnetTheme();

		if (!isset($theme['Theme']['name'])) {
			$theme['Theme'] = array();
		} else {
			$this->Controller->theme = $theme['Theme']['name'];
		}
		Configure::write('Theme',$theme['Theme']);
	}
}

?>