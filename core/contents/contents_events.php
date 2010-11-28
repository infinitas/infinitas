<?php
	/**
	 * @brief ContentsEvents plugin events.
	 *
	 * The events for the Contents plugin for setting up cache and the general
	 * configuration of the plugin.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contents
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ContentsEvents extends AppEvents{
		public function onAdminMenu($event){
			$menu['main'] = array(
				'Contents' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'index'),
				'Layouts' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index'),
			);

			return $menu;
		}

		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (isset($event->Handler->contentable) && $event->Handler->contentable && !$event->Handler->Behaviors->enabled('Contents.Contentable')) {					
					$event->Handler->Behaviors->attach('Contents.Contentable');
				}
			}
		}
	}