<?php
/**
 * CommentsEvents for the comments plugin
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Comments
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class CommentsEvents extends AppEvents {
/**
 * get the plugin information
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
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

/**
 * get the admin menu
 *
 * @param Event $event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Comments' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index'),
			'Active' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'InfinitasComment.active' => 1),
			'Pending' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'InfinitasComment.active' => 0, 'InfinitasComment.status' => 'approved'),
			'Spam' => array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'index', 'InfinitasComment.status' => 'spam')
		);

		return $menu;
	}

/**
 * get the required components
 *
 * @param Event $event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $event) {
		return array(
			'Comments.Comments'
		);
	}

/**
 * get the behaviors to attach
 *
 * @param Event $event
 */
	public function onAttachBehaviors(Event $event) {
		if(is_subclass_of($event->Handler, 'Model')) {
			if ($event->Handler->hasField('comment_count')) {
				if(!$event->Handler->Behaviors->enabled('Comments.Commentable')) {
					$event->Handler->Behaviors->attach('Comments.Commentable');
				}
			}
		}
	}

/**
 * get the css to load
 *
 * @param Event $event
 *
 * @return array
 */
	public function onRequireCssToLoad(Event $event) {
		return array(
			'Comments.comment'
		);
	}

/**
 * get the js to load
 *
 * @param Event $event
 *
 * @return array
 */
	public function onRequireJavascriptToLoad(Event $event) {
		return array(
			'Comments.comment'
		);
	}

/**
 * get the sitemap information
 *
 * @param Event $event
 *
 * @return array
 */
	public function onSiteMapRebuild(Event $event) {
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

/**
 * get the user profile elements to load
 *
 * @param Event $event
 *
 * @return array
 */
	public function onUserProfile(Event $event) {
		return array(
			'element' => 'profile'
		);
	}

}