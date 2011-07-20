<?php
class ScopedNumberTreeFixture extends CakeTestFixture {
	var $name = 'NumberTree';
	var $table = 'scoped_number_trees';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name'	=> array('type' => 'string','null' => false),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'category_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft'	=> array('type' => 'integer','null' => false),
		'rght'	=> array('type' => 'integer','null' => false)
	);
	
public $records = array(
			/* Nodes for category a */
			array(
				'id' => 'cat-a-1',
				'name' => '1 Root',
				'parent_id' => null,
				'category_id' => 'cat-a',
				'lft' => 1,
				'rght' => 14
			),
				array(
					'id' => 'cat-a-1-1',
					'name' => '1.1 Node',
					'parent_id' => 'cat-a-1',
					'category_id' => 'cat-a',
					'lft' => 2,
					'rght' => 3
				),
				array(
					'id' => 'cat-a-1-2',
					'name' => '1.2 Node',
					'parent_id' => 'cat-a-1',
					'category_id' => 'cat-a',
					'lft' => 4,
					'rght' => 11
				),
					array(
						'id' => 'cat-a-1-2-1',
						'name' => '1.2.1 Node',
						'parent_id' => 'cat-a-1-2',
						'category_id' => 'cat-a',
						'lft' => 5,
						'rght' => 6
					),
					array(
						'id' => 'cat-a-1-2-2',
						'name' => '1.2.2 Node',
						'parent_id' => 'cat-a-1-2',
						'category_id' => 'cat-a',
						'lft' => 7,
						'rght' => 8
					),
					array(
						'id' => 'cat-a-1-2-3',
						'name' => '1.2.3 Node',
						'parent_id' => 'cat-a-1-2',
						'category_id' => 'cat-a',
						'lft' => 9,
						'rght' => 10
					),
				array(
					'id' => 'cat-a-1-3',
					'name' => '1.3 Node',
					'parent_id' => 'cat-a-1',
					'category_id' => 'cat-a',
					'lft' => 12,
					'rght' => 13
				),
			array(
				'id' => 'cat-a-2',
				'name' => '2 Root',
				'parent_id' => null,
				'category_id' => 'cat-a',
				'lft' => 15,
				'rght' => 16
			)
	);
}