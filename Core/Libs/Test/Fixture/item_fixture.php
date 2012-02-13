<?php
	class ItemFixture extends CakeTestFixture {
		public $name = 'Item';

		public $table = 'items';

		public $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
			'ordering' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);

		public $records = array(
			array('id' => 1, 'name' => 'Item A', 'ordering' => 1),
			array('id' => 2, 'name' => 'Item B', 'ordering' => 2),
			array('id' => 3, 'name' => 'Item C', 'ordering' => 3),
			array('id' => 4, 'name' => 'Item D', 'ordering' => 4),
			array('id' => 5, 'name' => 'Item E', 'ordering' => 5),
		);
	}