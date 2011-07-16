<?php
	/**
	 * @brief ConfigsEvents for the Configs plugin to attach into the application
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Configs
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ConfigsEvents extends AppEvents{		
		public function onSetupConfig(){
		}

		public function onSetupCache(){
			return array(
				'name' => 'configs',
				'config' => array(
					'prefix' => 'core.configs.',
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Configuration' => array('controller' => 'configs', 'action' => 'index'),
				'Available' => array('controller' => 'configs', 'action' => 'available')
			);

			return $menu;
		}

		public function onGetRequiredFixtures($event){
			return array(
				'Configs.Config',
			);
		}
	}