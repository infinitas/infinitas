<?php
	/* Category Test cases generated on: 2010-03-13 15:03:54 : 1268486934*/
	App::import('Model', 'blog.Category');

	class CategoryTestCase extends CakeTestCase {
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
			$this->Category =& ClassRegistry::init('Category');
		}

		//function testCustomFindMethods(){
			//pr($this->Category->getActiveIds());
		//}

		function endTest() {
			unset($this->Category);
			ClassRegistry::flush();
		}

	}
?>