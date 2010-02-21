<?php
	/**
	 * Tags controller
	 *
	 * some admin methods for managing tags belonging to the blog posts
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package blog
	 * @subpackage blog.controllers.tags
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class TagsController extends BlogAppController{
		var $name = 'Tags';


		/**
		 * Index.
		 *
		 * Show some stats on the tags
		 *
		 * @todo show some stats.
		 */
		function admin_index(){

		}

		/**
		 * Remove unused tags.
		 *
		 * Gets a list of all the tags that are used in the blog and then
		 * removes the ones that are not bing used, and then redirects to the
		 * page the user was on.
		 *
		 * @return na
		 */
		function admin_clean_up(){
			$tags = ClassRegistry::init('Blog.PostsTag')->getUsedTagIds();

			$this->Session->setFlash(__('Tags seem prety clean', true));

			if (!empty($tagsInUse) && $this->Tag->deleteAll(array('Tag.id NOT' => $tagsInUse))) {
				$this->Session->setFlash(__('Tags not in use have been removed', true));
			}

			$this->redirect($this->referer());
		}
	}
?>