<?php
	/**
	 * CategoriesController for the management and display of categories and
	 * related data.
	 *
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Contents.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class GlobalCategoriesController extends ContentsAppController {
		public function beforeRender() {
			parent::beforeRender();

			if($this->request->params['admin']) {
				return;
			}

			if(!empty($this->viewVars['category'])) {
				$this->set('title_for_layout', $this->viewVars['category']['GlobalCategory']['title']);
				
				$canonicalUrl = $this->Event->trigger('Contents.slugUrl', array('type' => 'category', 'data' => array('GlobalCategory' => $this->viewVars['category']['GlobalCategory'])));
				$this->set('seoCanonicalUrl', $canonicalUrl['slugUrl']['Contents']);

				Configure::write('Website.description', $this->viewVars['category']['GlobalCategory']['meta_description']);
				Configure::write('Website.keywords', $this->viewVars['category']['GlobalCategory']['meta_keywords']);
				return;
			}

			if(Configure::read('Contents.GlobalCagegories')) {
				Configure::write('Website.description', Configure::read('Contents.GlobalCagegories.description'));
				Configure::write('Website.keywords', Configure::read('Contents.GlobalCagegories.keywords'));
			}

			$this->set('title_for_layout', __d('contents', 'Content Categories'));

			$this->set('seoContentIndex', Configure::read(sprintf('Contents.GlobalCagegories.robots.%s.index', $this->request->params['action'])));
			$this->set('seoContentFollow', Configure::read(sprintf('Contents.GlobalCagegories.robots.%s.follow', $this->request->params['action'])));
		}

		public function index() {
			$this->Paginator->settings = array(
				$this->modelClass => array(
					'conditions' => array(
						$this->modelClass . '.active' => 1,
						$this->modelClass . '.hide' => 0,
						$this->modelClass . '.parent_id IS NULL'
					)
				)
			);
			
			if(isset($this->request->params['category'])){
				$this->Paginator->settings[$this->modelClass]['conditions'][$this->modelClass . '.slug'] = $this->request->params['category'];
			}

			$categories = $this->Paginator->paginate();
			// redirect if there is only one category.
			if (count($categories) == 1 && Configure::read('Contents.GlobalCategory.auto_redirect')) {
				$this->redirect(
					array(
						'controller' => 'categories',
						'action' => 'view',
						$categories[0][$this->modelClass]['id']
					)
				);
			}

			$this->set('categories', $categories);

			$this->set('seoCanonicalUrl', Router::url(array('action' => 'index')));

			$this->set('seoContentIndex', Configure::read('Contents.GlobalCagegories.robots.index.index'));
			$this->set('seoContentFollow', Configure::read('Contents.GlobalCagegories.robots.index.follow'));
		}

		public function view() {
			if(empty($this->request->params['slug'])) {
				$this->notice('invalid');
			}

			$conditions = array(
				'GlobalContent.slug' => $this->request->params['slug']
			);

			$category = $this->GlobalCategory->find('getCategory', array('conditions' => $conditions));
			
			if(empty($category)){
				$this->notice('invalid');
			}

			// redirect if there is only one content item.
			if ((isset($category['Content']) && count($category['Content']) == 1) && Configure::read('Contents.GlobalCagegories.auto_redirect')) {

			}

			$this->set('category', $category);
		}

		public function admin_index() {
			$categories = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'title',
				'parent_id' => array(null => __('All'), 0 => __('Top Level Categories')) + $this->GlobalCategory->generateTreeList(),
				'group_id' => array(null => __('Public')) + $this->GlobalCategory->Group->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('filterOptions', 'categories'));
		}

		public function admin_view($id = null) {
			if (!$id) {
				$this->notice('invalid');
			}
			$this->set('category', $this->GlobalCategory->read(null, $id));
		}

		public function admin_edit($id = null) {
			unset($this->request->data['GlobalContent']['global_category_id']);

			parent::admin_edit($id);
		}

		public function admin_delete($id = null) {
			if (!$id) {
				$this->notice('invalid');
			}

			$count = $this->GlobalCategory->find('count', array('conditions' => array('Category.parent_id' => $id)));
			if ($count > 0) {
				$this->notice(
					sprintf(__('That %s has sub-categories'), $this->prettyModelName),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}

			$category = $this->GlobalCategory->read(null, $id);

			if (!empty($category['Content'])) {
				$this->notice(
					sprintf(__('That %s has content items, remove them before continuing'), $this->prettyModelName),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}

			return parent::admin_delete($id);
		}
	}