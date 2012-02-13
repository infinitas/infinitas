<?php
	class FeedsController extends FeedAppController {
		public function index(){
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
						//'Feed.group_id >= ' => $this->Session->read('Auth.User.group_id')
					)
				)
			);

			$this->set(compact('feeds'));
		}

		public function view(){
			if(!$this->params['slug']){
				$this->notice(
					__('Invalid feed selected'),
					array(
						'redirect' => true
					)
				);
			}

			$feeds = $this->Feed->getFeed($this->params['slug'], $this->Session->read('Auth.User.group_id'));

			if(empty($feeds)){
				$this->notice(
					__('The feed you have selected is not valid'),
					array(
						'redirect' => true
					)
				);
			}

			$raw = $this->Feed->find('first', array('conditions' => array('Feed.slug' => $this->params['slug'])));

			$this->set(compact('feeds', 'raw'));
		}

		public function admin_index() {
			$this->paginate = array('contain' => array('Group'));

			$feeds = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'plugin' => $this->Feed->getPlugins(),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('feeds', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			Configure::write('Wysiwyg.editor', 'text');

			$plugins = $this->Feed->getPlugins();
			$groups = $this->Feed->Group->find('list');
			$feedsFeeds = $this->Feed->find('list');
			$this->set(compact('plugins', 'groups', 'feedsFeeds'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);

			$plugins	 = $this->Feed->getPlugins();
			$controllers = $this->Feed->getControllers($this->data['Feed']['plugin']);
			$actions	 = $this->Feed->getActions($this->data['Feed']['plugin'], $this->data['Feed']['controller']);
			$feedsFeeds = $this->Feed->find('list');
			$groups	  = $this->Feed->Group->find('list');

			$this->set(compact('plugins', 'controllers', 'actions', 'feedsFeeds', 'groups'));
		}
	}