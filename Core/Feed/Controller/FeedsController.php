<?php
/**
 * FeedsController
 *
 * @package Infinitas.Feeds.Controller
 */

/**
 * FeedsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Feeds.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class FeedsController extends FeedAppController {
/**
 * Display all feeds (frontend)
 *
 * @return void
 */
	public function index() {
		$feeds = $this->Feed->find(
			'all',
			array(
				'fields' => array(
					'Feed.id',
					'Feed.name',
					'Feed.slug',
					'Feed.description',
					'Feed.plugin',
					'Feed.controller'
				),
				'conditions' => array(
					'Feed.active' => 1,
					//'Feed.group_id >= ' => $this->Auth->user('group_id')
				)
			)
		);

		$this->set(compact('feeds'));
	}

/**
 * View a feed
 *
 * @return void
 */
	public function view() {
		if (!$this->request->params['slug']) {
			$this->notice(
				__d('feed', 'Invalid feed selected'),
				array(
					'redirect' => true
				)
			);
		}

		$feeds = $this->Feed->getFeed($this->request->params['slug'], $this->Auth->user('group_id'));

		if (empty($feeds)) {
			$this->notice(
				__d('feed', 'The feed you have selected is not valid'),
				array(
					'redirect' => true
				)
			);
		}

		$raw = $this->Feed->find('first', array('conditions' => array('Feed.slug' => $this->request->params['slug'])));

		$this->set(compact('feeds', 'raw'));
	}

/**
 * Display all feeds
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array('contain' => array('Group'));

		$feeds = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'plugin' => $this->Feed->getPlugins(),
			'active' => Configure::read('CORE.active_options')
		);

		$this->set(compact('feeds', 'filterOptions'));
	}

/**
 * Create a new feed
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		Configure::write('Wysiwyg.editor', 'text');

		$plugins = $this->Feed->getPlugins();
		$groups = $this->Feed->Group->find('list');
		$feedsFeeds = $this->Feed->find('list');
		$this->set(compact('plugins', 'groups', 'feedsFeeds'));
	}

/**
 * Edit a feed
 *
 * @param string $id the feed id to edit
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

		$plugins	 = $this->Feed->getPlugins();
		$controllers = $this->Feed->getControllers($this->request->data['Feed']['plugin']);
		$actions	 = $this->Feed->getActions($this->request->data['Feed']['plugin'], $this->request->data['Feed']['controller']);
		$feedsFeeds = $this->Feed->find('list');
		$groups	  = $this->Feed->Group->find('list');

		$this->set(compact('plugins', 'controllers', 'actions', 'feedsFeeds', 'groups'));
	}

}