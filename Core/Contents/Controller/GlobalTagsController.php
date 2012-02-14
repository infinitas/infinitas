<?php
	class GlobalTagsController extends ContentsAppController {
		public function beforeFilter(){
			parent::beforeFilter();

			$this->helpers[] = 'Filter.Filter';
			return true;
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

			$this->set(compact('tags', 'filterOptions'));
		}
	}
