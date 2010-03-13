<?php
	/* Post Test cases generated on: 2010-03-13 15:03:45 : 1268486985*/
	App::import('Model', 'blog.Post');

	class PostTestCase extends CakeTestCase {
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
			$this->Post =& ClassRegistry::init('Post');
		}

		function endTest() {
			unset($this->Post);
			ClassRegistry::flush();
		}

	}
?>