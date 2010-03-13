<?php
	/* Tag Test cases generated on: 2010-03-13 15:03:17 : 1268487017*/
	App::import('Model', 'blog.Tag');

	class TagTestCase extends CakeTestCase {
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
			$this->Tag =& ClassRegistry::init('Tag');
		}

		function endTest() {
			unset($this->Tag);
			ClassRegistry::flush();
		}

	}
?>