<?php
	class FeedItemsController extends FeedAppController {
		var $name = 'FeedItems';

		function admin_index() {
			$this->paginate = array('contain' => array('Group'));

			$feedItems = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('feedItems', 'filterOptions'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->FeedItem->create();
				if ($this->FeedItem->saveAll($this->data)) {
					$this->Session->setFlash('Your Feed Item has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$plugins = $this->FeedItem->getPlugins();
			$groups = $this->FeedItem->Group->find('list');
			$feeds = $this->FeedItem->Feed->find('list');
			$this->set(compact('plugins', 'groups', 'feeds'));
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That Feed Item could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->FeedItem->save($this->data)) {
					$this->Session->setFlash(__('Your Feed Item has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your Feed Item could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Feed->find(
					'first',
					array(
						'conditions' => array(
							'FeedItem.id' => $id
						),
						'contain' => array(
							'Feed'
						)
					)
				);
			}

			$plugins     = $this->FeedItem->getPlugins();
			$controllers = $this->FeedItem->getControllers($this->data['FeedItem']['plugin']);
			$actions     = $this->FeedItem->getActions($this->data['FeedItem']['plugin'], $this->data['FeedItem']['controller']);
			$feeds       = $this->FeedItem->FeedItem->find('list');
			$groups      = $this->FeedItem->Group->find('list');

			$this->set(compact('plugins', 'controllers', 'actions', 'feeds', 'groups'));
		}
	}