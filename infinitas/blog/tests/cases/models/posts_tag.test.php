<?php
	/* PostsTag Test cases generated on: 2010-03-13 15:03:02 : 1268487002*/
	App::import('Model', 'blog.PostsTag');

	class PostsTagTestCase extends CakeTestCase {
		var $fixtures = array(
			'plugin.blog.category',
			'plugin.blog.post',
			'plugin.blog.posts_tag',
			'plugin.blog.tag',

			'plugin.management.user',
			'plugin.management.group',
			'plugin.management.aco',
			'plugin.management.aro',
			'plugin.management.aros_aco',
		);

		function startTest() {
			$this->PostsTag =& ClassRegistry::init('PostsTag');
		}

		function endTest() {
			unset($this->PostsTag);
			ClassRegistry::flush();
		}

	}
?>