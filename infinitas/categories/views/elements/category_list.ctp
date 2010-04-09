<?php
	$categories = ClassRegistry::init('Categories.Category')->generatetreelist();

	pr($categories);
?>