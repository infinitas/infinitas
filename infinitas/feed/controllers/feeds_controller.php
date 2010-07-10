<?php
	class FeedsController extends FeedAppController {
		var $name = 'Feeds';

		function get_feed($id = null){
			if(!$id){
				$this->Session->setFlash(__('Invalid feed selected', true));
				$this->redirect($this->referer());
			}

			$feed = $this->Feed->getFeed($id, $this->Session->read('Auth.User.group_id'));

			if(empty($feed)){
				$this->Session->setFlash(__('The feed you have selected is not valid', true));
				$this->redirect($this->referer());
			}



			$this->set(compact('feed'));
		}

		function admin_index() {
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

		function admin_add() {
			if (!empty($this->data)) {
				$this->Feed->create();
				if ($this->Feed->saveAll($this->data)) {
					$this->Session->setFlash('Your Feed has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$plugins = $this->Feed->getPlugins();
			$groups = $this->Feed->Group->find('list');
			$feedItems = $this->Feed->FeedItem->find('list');
			$this->set(compact('plugins', 'groups', 'feedItems'));
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That Feed could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Feed->save($this->data)) {
					$this->Session->setFlash(__('Your Feed has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your Feed could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Feed->find(
					'first',
					array(
						'conditions' => array(
							'Feed.id' => $id
						),
						'contain' => array(
							'FeedItem'
						)
					)
				);
			}

			$plugins     = $this->Feed->getPlugins();
			$controllers = $this->Feed->getControllers($this->data['Feed']['plugin']);
			$actions     = $this->Feed->getActions($this->data['Feed']['plugin'], $this->data['Feed']['controller']);
			$feedItems   = $this->Feed->FeedItem->find('list');
			$groups      = $this->Feed->Group->find('list');

			$this->set(compact('plugins', 'controllers', 'actions', 'feedItems', 'groups'));
		}
	}