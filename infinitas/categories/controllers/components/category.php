<?php
class CategoryComponent extends Object {
	function startup(&$Controller) {
		$categories = ClassRegistry::init('Categories.Category')->generatetreelist();

		$Controller->set('categories', $categories);
	}

}
?>