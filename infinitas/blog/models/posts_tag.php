<?php
	/**
	 *
	 *
	 */
	class PostsTag extends BlogAppModel{
		var $name = 'PostsTag';

		function getUsedTagIds(){
			$tags = $this->find(
				'all',
				array(
					'fields' => array(
						'DISTINCT(PostsTag.tag_id) as tag_id'
					)
				)
			);

			return Set::extract('/PostsTag/tag_id', $tags);
		}
	}
?>