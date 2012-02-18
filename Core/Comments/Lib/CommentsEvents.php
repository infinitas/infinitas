<?php
	/**
	 * @brief CommentsEvents for the comments plugin
	 * 
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class CommentsEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Comments',
				'description' => 'See what your users have to say',
				'icon' => '/comments/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array(
					'plugin' => 'comments',
					'controller' => 'infinitas_comments',
					'action' => 'index'
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Comments' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index'),
				'Active' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'Comment.active' => 1),
				'Pending' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'Comment.active' => 0, 'Comment.status' => 'approved'),
				'Spam' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'Comment.status' => 'spam')
			);

			return $menu;
		}
		
		public function onSetupConfig(){
			return Configure::load('Comments.config');
		}

		public function  onRequireComponentsToLoad($event = null) {
			return array(
				'Comments.Comments'
			);
		}
		
		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model')) {
				if ($event->Handler->hasField('comment_count')) {					
					if(!$event->Handler->Behaviors->enabled('Comments.Commentable')){
						$event->Handler->Behaviors->attach('Comments.Commentable');
					}
				}
			}
		}

		public function onRequireCssToLoad($event){
			return array(
				'Comments.comment'
			);
		}

		public function onRequireJavascriptToLoad($event){
			return array(
				'Comments.comment'
			);
		}

		public function onSiteMapRebuild($event){
			$newestRow = ClassRegistry::init('Comments.Comment')->getNewestRow();

			if(!$newestRow) {
				return false;
			}
			
			return array(
				array(
					'url' => Router::url(array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'admin' => false, 'prefix' => false), true),
					'last_modified' => $newestRow,
					'change_frequency' => ClassRegistry::init('Comments.InfinitasComment')->getChangeFrequency(),
					'priority' => 0.8
				)
			);
		}

		public function onUserProfile($event){
			return array(
				'element' => 'profile'
			);
		}
	 }