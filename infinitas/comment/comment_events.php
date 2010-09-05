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
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {			
				if (array_key_exists('comment_count', $event->Handler->_schema)  && !$event->Handler->Behaviors->enabled('Comments.Commentable')) {					
					$event->Handler->Behaviors->attach('Comment.Commentable');					
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