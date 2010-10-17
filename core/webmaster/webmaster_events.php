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

	class WebmasterEvents extends AppEvents{
		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Edit Robots' => array('plugin' => 'webmaster', 'controller' => 'robots', 'action' => 'edit'),
				'View Sitemap' => array('plugin' => 'webmaster', 'controller' => 'site_maps', 'action' => 'view'),
				'Rebuild Sitemap' => array('plugin' => 'webmaster', 'controller' => 'site_maps', 'action' => 'rebuild')
			);

			return $menu;
		}

		public function onSetupConfig(){
			return Configure::load('webmaster.config');
		}

		public function onSetupCache(){
			return array(
				'name' => 'webmaster',
				'config' => array(
					'prefix' => 'webmaster.',
				)
			);
		}
		
		public function onSetupRoutes(){
			Router::connect('/sitemap', array('plugin' => 'webmaster', 'controller' => 'site_maps', 'action' => 'index', 'admin' => false, 'prefix' => ''));
		}

		public function onSetupExtentions(){
			return array(
				'xml'
			);
		}
	}