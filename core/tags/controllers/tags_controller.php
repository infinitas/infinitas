<?php
	class TagsController extends TagsAppController {
		/**
		 * Name
		 *
		 * @var string $name
		 * @access public
		 */
		public $name = 'Tags';

		/**
		 * Admin Index
		 *
		 * @return void
		 * @access public
		 */
		public function admin_index() {
			$this->set('tags', $this->paginate());
		}
	}
