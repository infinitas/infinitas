<?php
	/**
	 * Blog App Controller class file.
	 *
	 * The parent class that all the blog plugin controller classes extend from.
	 * This is used to make functionality that is needed all over the blog
	 * plugin.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package blog
	 * @subpackage blog.controllers.blogAppController
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	class BlogAppController extends AppController {
		public $helpers = array(
			'Blog.Blog', 'Blog.PostLayout', 'Blog.CommentLayout',
			'Google.Chart'
		);
		
		/**
		 * beforeFilter callback
		 *
		 * this method is run before any of the controllers in the blog plugin.
		 * It is used to set up a cache config and some other variables that are
		 * needed throughout the plugin.
		 *
		 * @param nothing $
		 * @return nothing
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			$this->set('postLatest', ClassRegistry::init('Blog.Post')->getLatest());
			$this->set('postPending', ClassRegistry::init('Blog.Post')->getPending());
			$this->set('postPopular', ClassRegistry::init('Blog.Post')->getPopular());

			$this->set('commentCount', ClassRegistry::init('Comment.Comment')->getCounts('Post'));
		}

		/**
		 * afterFilter callback.
		 *
		 * used to do stuff before the code is rendered but after all the
		 * controllers have finnished.
		 *
		 * @return nothing
		 */
		public function afterFilter() {
			parent::afterFilter();
		}
	}