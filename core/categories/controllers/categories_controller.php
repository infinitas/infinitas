<?php
	/**
	* Comment Template.
	*
	* @todo Implement .this needs to be sorted out.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package sort
	* @subpackage sort.comments
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5a
	*/

	class CategoriesController extends CategoriesAppController {
		var $name = 'Categories';

		var $helpers = array(
			'Filter.Filter',
			'Text'
		);

		function index() {
			if(isset($this->params['category'])){
				$this->paginate['Category']['conditions']['Category.slug'] = $this->params['category'];
			}

			$categories = $this->paginate();
			// redirect if there is only one category.
			if (count($categories) == 1 && Configure::read('Cms.auto_redirect')) {
				$this->redirect(
					array(
						'controller' => 'categories',
						'action' => 'view',
						$categories[0]['Category']['id']
					)
				);
			}

			$this->set('categories', $categories);
		}

		function view() {
			if(isset($this->params['category'])){
				$conditions['Category.slug'] = $this->params['category'];
			}

			$category = $this->Category->find(
				'first',
				array(
					'conditions' => $conditions
				)
			);

			// redirect if there is only one content item.
			if ((isset($category['Content']) && count($category['Content']) == 1) && Configure::read('Cms.auto_redirect')) {
				$this->redirect(
					array(
						'controller' => 'contents',
						'action' => 'view',
						$category['Content'][0]['id']
					)
				);
			}
			
			else if(empty($category)){
				$this->Session->setFlash(__('Invalid category', true));
				$this->redirect($this->referer());
			}

			$this->set('title_for_layout', $category['Category']['title']);
			$this->set('category', $category);
		}

		function admin_index() {
			$categories = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'title',
				'parent_id' => array(null => __('All', true), 0 => __('Top Level Categories', true)) + $this->Category->generatetreelist(),
				'group_id' => array(null => __('Public', true)) + $this->Category->Group->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);
			$this->set(compact('filterOptions','categories'));
		}

		function admin_view($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('Invalid category', true));
				$this->redirect(array('action' => 'index'));
			}
			$this->set('category', $this->Category->read(null, $id));
		}

		function admin_add() {
			parent::admin_add();

			$parents = array(__('Top Level Category', true)) + $this->Category->generatetreelist();
			$groups = $this->Category->Group->find('list');
			$this->set(compact('parents', 'groups'));
		}

		function admin_edit($id = null) {
			parent::admin_edit($id);

			$parents = array(__('None', true)) + $this->Category->generatetreelist();
			$groups = $this->Category->Group->find('list');
			$this->set(compact('parents', 'groups'));
		}

		function admin_delete($id = null) {
			if (!$id) {
				$this->Session->setFlash('That Category could not be found', true);
				$this->redirect($this->referer());
			}

			$count = $this->Category->find('count', array('conditions' => array('Category.parent_id' => $id)));
			if ($count > 0) {
				$this->Session->setFlash(__('This Category has sub-categories.', true));
				$this->redirect($this->referer());
			}

			$category = $this->Category->read(null, $id);

			if (!empty($category['Content'])) {
				$this->Session->setFlash(__('There are still content itmes in this category. Remove them and try again.', true));
				$this->redirect($this->referer());
			}

			return parent::admin_delete($id);
		}
	}