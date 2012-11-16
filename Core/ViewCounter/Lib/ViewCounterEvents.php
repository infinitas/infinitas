<?php
/**
 * Events for the views behavior
 *

 *
 * @filesource
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ViewCounter.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ViewCounterEvents extends AppEvents {
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'dashboard'),
			'Reports' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'reports'),
			'Referers' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'referers'),
			//'Custom' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'custom'),
		);

		return $menu;
	}

	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'View Counts',
			'description' => 'View your sites traffic',
			'icon' => '/view_counter/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'dashboard')
		);
	}

	public function onSetupCache(Event $Event, $data = null) {
		return array(
			'name' => 'view_counter',
			'config' => array(
				'prefix' => 'view_counter.',
			)
		);
	}

	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'ViewCounter.ViewCounter'
		);
	}

	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'ViewCounter.ViewCounter'
		);
	}

/**
 * Called before blog post is echo'ed
 */
	public function onBlogBeforeContentRender(Event $Event, $data) {
		if(!isset($Event->Handler->params['models'][0]) || !in_array('view_count', (array)Configure::read('Blog.before'))) {
			return false;
		}

		$Model = ClassRegistry::init(Inflector::camelize($Event->Handler->params['plugin']).'.'.$Event->Handler->params['models'][0]);
		$Model->Behaviors->attach('ViewCounter.Viewable');
		$views = $Model->getToalViews($class);

		return $this->__views($views);
	}

/**
 * Called after blog post is echo'ed
 */
	public function onBlogAfterContentRender(Event $Event, $data) {
		if(!isset($Event->Handler->params['models'][0]) || !in_array('view_count', (array)Configure::read('Blog.after'))) {
			return false;
		}

		$Model = ClassRegistry::init(Inflector::camelize($Event->Handler->params['plugin']).'.'.$Event->Handler->params['models'][0]);
		$Model->Behaviors->attach('ViewCounter.Viewable');
		$views = $Model->getToalViews($data['post']['Post']['id']);

		return $this->__views($views, 'Blog.BlogPost');
	}

	public function onSetupRoutes(Event $Event, $data = null) {
		InfinitasRouter::connect(
			'/admin/view_counter',
			array(
				'plugin' => 'view_counter',
				'controller' => 'view_counter_views',
				'action' => 'dashboard',
				'admin' => true
			)
		);
	}

/**
 * Include some css
 */
	public function onRequireCssToLoad(Event $Event, $data = null) {
		return array(
			'ViewCounter.view_counter'
		);
	}

/**
 * generate some info for the number of views
 *
 * @param int the number of views
 * @return string
 */
	private function __views($views = 0, $model = null) {
		$average = ClassRegistry::init('ViewCounter.ViewCounterView')->getAverage($model);

		switch($views) {
			case 0:
				$text = __('Go on, be the first to view this post');
				break;

			case $views < $average / 10:
				$text = sprintf(__('%s views (new post)'), $views);
				break;

			default:
				$text = sprintf(
					__('<span class="%s popular">%s views</span>'),
					$views > $average * 1.25 ? 'extra' : '',
					$views
				);
				break;
		}

		return $text;
	}

/**
 * attach the reporting behavior for models with views
 */
	public function onAttachBehaviors(Event $Event) {
		if($Event->Handler->shouldAutoAttachBehavior()) {
			if ($Event->Handler->hasField('views')) {
				$Event->Handler->Behaviors->attach('ViewCounter.ViewableReporting');
			}
		}
	}

/**
 * @copydoc AppEvents::onRunCrons()
 *
 * The view counter crons clear out views from localhost.
 *
 * @note You should disable crons if you want to test some functionality
 * on localhost.
 */
	public function onRunCrons(Event $Event) {
		ClassRegistry::init('ViewCounter.ViewCounterView')->clearLocalhost();
		return true;
	}

	public function onGetRequiredFixtures(Event $Event) {
		return array(
			'ViewCounter.ViewCounterView',
		);
	}

}