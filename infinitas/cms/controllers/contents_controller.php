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

	class ContentsController extends CmsAppController {
		public $name = 'Contents';

		/**
		* Helpers.
		*
		* @access public
		* @var array
		*/
		public $helpers = array('Filter.Filter');

		public function beforeFilter(){
			parent::beforeFilter();
		}

		public function index() {
			if(isset($this->params['id'])){
				$ids = $this->Content->find(
					'list',
					array(
						'fields' => array(
							'Content.id', 'Content.id'
						),
						'conditions' => array(
							'Content.category_id' => $this->params['id']
						)
					)
				);

				$this->paginate = array(
					'conditions' => array(
						'Content.id' => $ids
					)
				);
			}

			$this->Content->order = $this->Content->_order;
			$contents = $this->paginate();

			if(count($contents) == 1 && Configure::read('Cms.auto_redirect')){
				$this->params['slug'] = $contents[0]['Content']['slug'];
				$this->view();
			}

			$this->set('contents', $this->paginate());
		}

		public function view() {
			if (!isset($this->params['slug'])) {
				$this->Session->setFlash( __('Invalid content selected', true) );
				$this->redirect($this->referer());
			}

			$this->set('content', $this->Content->getContentPage($this->params['slug']));
			$this->render('view');
		}

		public function admin_index() {
			$this->Content->recursive = 1;
			$this->Content->order = $this->Content->_order;
			$contents = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'title',
				'category_id' => array(null => __('All', true), null => __('Top Level Categories', true)) + $this->Content->generateCategoryList(),
				'group_id' => array(null => __('Public', true)) + $this->Content->Group->find('list'),
				'layout_id' => array(null => __('All', true)) + $this->Content->Layout->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('contents','filterOptions'));
		}

		public function admin_view($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('Invalid content', true));
				$this->redirect(array('action' => 'index'));
			}
			$this->set('content', $this->Content->read(null, $id));
		}

		public function admin_add() {
			if (!empty($this->data)) {
				$this->Content->create();
				if ($this->Content->saveAll($this->data)) {
					$this->Session->setFlash(__('The content has been saved', true));
					$this->redirect(array('action' => 'index'));
				}else {
					$this->Session->setFlash(__('The content could not be saved. Please, try again.', true));
				}
			}

			//$categories = array(__('Please select', true)) + $this->Content->Category->generatetreelist();
			$groups = array(__('Public', true)) + $this->Content->Group->generatetreelist();
			$layouts = $this->Content->Layout->find('list');
			$this->set(compact('groups','layouts'));
		}

		public function admin_edit($id = null) {
			if (!$id && empty($this->data)) {
				$this->Session->setFlash(__('Invalid content', true));
				$this->redirect(array('action' => 'index'));
			}
			if (!empty($this->data)) {
				if ($this->Content->saveAll($this->data)) {
					$this->Session->setFlash(__('The content has been saved', true));
					$this->redirect(array('action' => 'index'));
				}else {
					$this->Session->setFlash(__('The content could not be saved. Please, try again.', true));
				}
			}
			if (empty($this->data)) {
				$this->data = $this->Content->lock(null, $id);
				if ($this->data === false) {
					$this->Session->setFlash(__('The content item is currently locked', true));
					$this->redirect($this->referer());
				}
			}

			$groups = array(__('Public', true)) + $this->Content->Group->generatetreelist();
			$layouts = $this->Content->Layout->find('list');
			$this->set(compact('categories','groups','layouts'));
		}
	}