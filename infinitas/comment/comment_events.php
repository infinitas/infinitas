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

	 final class CommentEvents extends AppEvents{
		public function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model')) {
				if ($event->Handler->hasField('comment_count')) {					
					$event->Handler->bindModel(
						array(
							'hasMany' => array(
								$event->Handler->name.'Comment' => array(
									'className' => 'Comment.Comment',
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
					
					if(!$event->Handler->Behaviors->enabled('Comment.Commentable')){
						$event->Handler->Behaviors->attach('Comment.Commentable');
					}
				}
			}
		}

		public function onRequireCssToLoad(&$event){
			return array(
				'/comment/css/comment'
			);
		}

		public function onRequireJavascriptToLoad(&$event){
			return array(
				'/comment/js/comment'
			);
		}
	 }