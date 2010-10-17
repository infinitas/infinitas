<?php
	class TagsController extends TagsAppController {
		/**
		 * Name
		 *
		 * @var string $name
		 * @access public
		 */
		public $name = 'Tags';

		public function beforeFilter(){
			parent::beforeFilter();

			$this->helpers[] = 'Filter.Filter';
		}

		/**
		 * Admin Index
		 *
		 * @return void
		 * @access public
		 */
		public function admin_index() {
			$tags = $this->paginate();

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'identifier',
				'keyname'
			);

			$this->set(compact('tags','filterOptions'));
		}
	}
