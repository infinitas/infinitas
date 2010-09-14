<?php
	class FeedItemsController extends FeedAppController {
		public $name = 'FeedItems';

		public function admin_index() {
			$this->paginate = array('contain' => array('Group'));

			$feedItems = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'plugin' => $this->FeedItem->getPlugins(),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('feedItems', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			$plugins = $this->FeedItem->getPlugins();
			$groups = $this->FeedItem->Group->find('list');
			$feeds = $this->FeedItem->Feed->find('list');
			$this->set(compact('plugins', 'groups', 'feeds'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);

			$plugins     = $this->FeedItem->getPlugins();
			$controllers = $this->FeedItem->getControllers($this->data['FeedItem']['plugin']);
			$actions     = $this->FeedItem->getActions($this->data['FeedItem']['plugin'], $this->data['FeedItem']['controller']);
			$feeds       = $this->FeedItem->FeedItem->find('list');
			$groups      = $this->FeedItem->Group->find('list');

			$this->set(compact('plugins', 'controllers', 'actions', 'feeds', 'groups'));
		}
	}