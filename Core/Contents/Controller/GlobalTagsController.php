<?php
	class GlobalTagsController extends ContentsAppController {
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
