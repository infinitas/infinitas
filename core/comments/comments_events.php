<?php
	/**
	 * Events for the comments plugin
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package infinitas.comments
	 * @subpackage infinitas.comments.events
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
				'author' => 'Infinitas'
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Comments' => array('controller' => false, 'action' => false),
				'Active' => array('controller' => 'comments', 'action' => 'index', 'Comment.active' => 1),
				'Pending' => array('controller' => 'comments', 'action' => 'index', 'Comment.active' => 0, 'Comment.status' => 'approved'),
				'Spam' => array('controller' => 'comments', 'action' => 'index', 'Comment.status' => 'spam')
			);

			return $menu;
		}
		
		public function onSetupConfig(){
			return Configure::load('comments.config');
		}
		
		public function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model')) {
				if ($event->Handler->hasField('comment_count')) {					
					$event->Handler->bindModel(
						array(
							'hasMany' => array(
								$event->Handler->name.'Comment' => array(
									'className' => 'Comments.Comment',
									'foreignKey' => 'foreign_id',
									'limit' => 5,
									'order' => array(
										$event->Handler->name.'Comment.created' => 'desc'
									),
									'fields' => array(
										$event->Handler->name.'Comment.id',
										$event->Handler->name.'Comment.class',
										$event->Handler->name.'Comment.foreign_id',
										$event->Handler->name.'Comment.user_id',
										$event->Handler->name.'Comment.email',
										$event->Handler->name.'Comment.comment',
										$event->Handler->name.'Comment.active',
										$event->Handler->name.'Comment.status',
										$event->Handler->name.'Comment.created'
									),
									'conditions' => array(
										'or' => array(
											$event->Handler->name.'Comment.active' => 1
										)
									),
									'dependent' => true
								)
							)
						),
						false
					);
					
					if(!$event->Handler->Behaviors->enabled('Comments.Commentable')){
						$event->Handler->Behaviors->attach('Comments.Commentable');
					}
				}
			}
		}

		public function onRequireCssToLoad(&$event){
			return array(
				'/comments/css/comment'
			);
		}

		public function onRequireJavascriptToLoad(&$event){
			return array(
				'/comments/js/comment'
			);
		}
	 }