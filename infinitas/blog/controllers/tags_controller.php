<?php
	/**
	 *
	 *
	 */
	class TagsController extends BlogAppController{
		var $name = 'Tags';

		function admin_clean_up(){
			$tags = ClassRegistry::init('Blog.PostsTag')->find(
				'all',
				array(
					'fields' => array(
						'DISTINCT(PostsTag.tag_id) as tag_id',
						'DISTINCT(PostsTag.tag_id) as tag_id',
					)
				)
			);

			$tagsInUse = Set::extract('/PostsTag/tag_id', $tags);

			$this->Session->setFlash(__('Tags seem prety clean', true));

			if (!empty($tagsInUse) && $this->Tag->deleteAll(array('Tag.id NOT' => $tagsInUse))) {
				$this->Session->setFlash(__('Tags not in use have been removed', true));
			}
			$this->redirect($this->referer());
		}
	}
?>