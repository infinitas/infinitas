<?php
	/**
	 *
	 *
	 */
	class GlobalLayoutsController extends ContentsAppController {
		/**
		 * Helpers.
		 *
		 * @access public
		 * @var array
		 */
		public $helpers = array('Filter.Filter');

		/**
		 * Its pointless trying to use a wysiwyg here, so we will just put if off
		 * completely for the layouts.
		 */
		public function beforeRender(){
			parent::beforeRender();
			Configure::write('Wysiwyg.editor', 'text');
		}

		public function admin_index() {
			$this->paginate = array(
				'order' => array(
					'GlobalLayout.model',
					'GlobalLayout.name'
				)
			);
			$layouts = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);

			$this->set(compact('layouts', 'filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();

			$plugins = $this->GlobalLayout->getPlugins();
			$this->set(compact('plugins'));
		}

		public function admin_edit($id = null, $query = array()) {
			$this->GlobalLayout->GlobalContent->hasField('id');
			parent::admin_edit($id, $query);

			$plugins = $this->GlobalLayout->getPlugins();
			$models = $this->GlobalLayout->getModels($this->data['GlobalLayout']['plugin']);
			$this->set(compact('plugins', 'models'));
		}
	}