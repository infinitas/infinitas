<?php
	class FeedsController extends FeedAppController {
		public $name = 'Feeds';

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
					),
					'contain' => array(
						'FeedItem' => array(
							'fields' => array(
								'FeedItem.name',
								'FeedItem.description',
								'FeedItem.plugin',
								'FeedItem.controller'
							),
							'conditions' => array(
								'FeedItem.active' => 1,
								//'FeedItem.group_id >= ' => $this->Session->read('Auth.User.group_id')
							)
						)
					)
				)
			);

			$this->set(compact('feeds'));
		}

		public function get_feed(){
			if(!$this->params['id']){
				$this->Session->setFlash(__('Invalid feed selected', true));
				$this->redirect($this->referer());
			}

			$feed = $this->Feed->getFeed($this->params['id'], $this->Session->read('Auth.User.group_id'));

			if(empty($feed)){
				$this->Session->setFlash(__('The feed you have selected is not valid', true));
				$this->redirect($this->referer());
			}

			$raw = $this->Feed->find('first', array('conditions' => array('Feed.id' => $this->params['id'])));

			$this->set(compact('feed', 'raw'));
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

			$plugins = $this->Feed->getPlugins();
			$groups = $this->Feed->Group->find('list');
			$feedItems = $this->Feed->FeedItem->find('list');
			$this->set(compact('plugins', 'groups', 'feedItems'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);

			$plugins     = $this->Feed->getPlugins();
			$controllers = $this->Feed->getControllers($this->data['Feed']['plugin']);
			$actions     = $this->Feed->getActions($this->data['Feed']['plugin'], $this->data['Feed']['controller']);
			$feedItems   = $this->Feed->FeedItem->find('list');
			$groups      = $this->Feed->Group->find('list');

			$this->set(compact('plugins', 'controllers', 'actions', 'feedItems', 'groups'));
		}
	}