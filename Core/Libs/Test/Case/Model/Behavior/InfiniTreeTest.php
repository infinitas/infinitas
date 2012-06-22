	<?php
	App::import('lib', 'libs.test/app_model_test.php');

	class ScopedNumberTree123 extends CakeTestModel {
		public $actsAs = array('Libs.InfiniTree' => array('scopeField' => 'category_id'));
	}

	class ScopedCounterNumberTree extends CakeTestModel {
		public $actsAs = array('Libs.InfiniTree' => array('scopeField' => 'category_id', 'counterCache' => true, 'directCounterCache' => true));
	}

	class ScopedNumberTreeTest extends CakeTestCase {
		public $setup = array(
			'model' => 'Libs.ScopedNumberTree',
			'fixtures' => array(
				'do' => array(
					'Libs.ScopedNumberTree',
					'Libs.ScopedCounterNumberTree'
				)
			)
		);

		public function testFixtureIntegrity() {
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
		}

		public function testTreeSave() {
			//Test invalid values
			$this->assertFalse($this->ScopedNumberTree->treeSave());
			$this->assertFalse($this->ScopedNumberTree->treeSave(false));
			$this->assertFalse($this->ScopedNumberTree->treeSave(array()));
			$this->assertFalse($this->ScopedNumberTree->treeSave(array('foo' => 'bar')));
			$this->assertFalse($this->ScopedNumberTree->treeSave(array('data' => 'field'), array('foo' => 'bar')));


			//make sure the treeSave doesnt affect behavior of children()
			$numChildrenCatA = count($this->ScopedNumberTree->children(array('id' => null, 'scope' => 'cat-a')));

			$data = array('ScopedNumberTree' => array(
				array(
					'name' => 'United Kingdom',
					'ScopedNumberTree' => array(
						array('name' => 'Sales'),
						array('name' => 'Marketing'),
						array('name' => 'R&D')
					)
				),
				array(
					'name' => 'Belgium',
					'ScopedNumberTree' => array(
						array('name' => 'Sales'),
						array('name' => 'Marketing'),
						array('name' => 'R&D')
					)
				)
			));

			$expected = array('United Kingdom', '_Sales', '_Marketing', '_R&D', 'Belgium', '_Sales', '_Marketing', '_R&D');
			$this->assertTrue($this->ScopedNumberTree->treeSave($data, array('scope' => 'cat-new')));
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-new'))));

			//This shouldnt have changed
			$this->assertEqual($numChildrenCatA, count($this->ScopedNumberTree->children(array('id' => false, 'scope' => 'cat-a'))));

			//Check if the trees got corrupt
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-new');
			$this->assertIdentical($validTree, true);
		}

		public function testAddingNodes() {
			/**
			 * Root nodes
			 */

			//Try adding a root node without specifying the scope field
			//should fail because we cant get the scope
			$data = array(
				'name' => '3 Root',
				'parent_id' => null
			);
			$this->ScopedNumberTree->create();
			$this->assertFalse($this->ScopedNumberTree->save($data));

			//should work with the scope
			$data = array(
				'name' => '3 Root',
				'category_id' => 'cat-a',
				'parent_id' => null
			);
			$this->ScopedNumberTree->create();
			$this->assertTrue($this->ScopedNumberTree->save($data));


			//With the scope variable
			$data = array(
				'name' => '1.3.1 node',
				'parent_id' => 'cat-a-1-3',
				'category_id' => 'cat-a'
			);
			$this->ScopedNumberTree->create();
			$this->assertTrue($this->ScopedNumberTree->save($data));

			//Without the scope variable
			$data = array(
				'name' => '1.3.2 node',
				'parent_id' => 'cat-a-1-3'
			);
			$this->ScopedNumberTree->create();
			$this->assertTrue($this->ScopedNumberTree->save($data));


			//Should be able to modify the name of a node without breaking stuff
			$data = array(
				'name' => '1.2 Node (edited)'
			);
			$this->ScopedNumberTree->id = 'cat-a-1-2';
			$this->assertTrue($this->ScopedNumberTree->save($data));
			//Move 1.2.3 into 1.1 (becomes 1.1.1)
			$data = array(
				'name' => '1.1.1 Node (edited)',
				'parent_id' => 'cat-a-1-1'
			);
			$this->ScopedNumberTree->id = 'cat-a-1-2-3';
			$this->assertTrue($this->ScopedNumberTree->save($data));

			//Check the structure after all the changes
			$expected = array('1 Root', '_1.1 Node', '__1.1.1 Node (edited)', '_1.2 Node (edited)', '__1.2.1 Node', '__1.2.2 Node', '_1.3 Node', '__1.3.1 node', '__1.3.2 node', '2 Root', '3 Root');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));

			//Verify trees after saving a bunch of new nodes
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
		}

		public function testDeletingNodes() {
			//Try to delete a leaf node
			$this->ScopedNumberTree->id = 'cat-a-1-2-1';
			$this->assertTrue($this->ScopedNumberTree->delete());
			$expected = array('1 Root', '_1.1 Node', '_1.2 Node', '__1.2.2 Node', '__1.2.3 Node', '_1.3 Node', '2 Root');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));

			//Try to delete a node with children
			$this->ScopedNumberTree->id = 'cat-a-1-2';
			$this->assertTrue($this->ScopedNumberTree->delete());
			$expected = array('1 Root', '_1.1 Node', '_1.3 Node', '2 Root');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));


			//Verify trees after deleting a bunch of new nodes
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
		}

		public function testMovingNodes() {
			//Check invalid id's
			$this->ScopedNumberTree->id = false;
			$this->assertFalse($this->ScopedNumberTree->movedown());
			$this->assertFalse($this->ScopedNumberTree->movedown(false));
			$this->assertFalse($this->ScopedNumberTree->moveup());
			$this->assertFalse($this->ScopedNumberTree->moveup(false));
			$this->assertFalse($this->ScopedNumberTree->removefromtree());
			$this->assertFalse($this->ScopedNumberTree->removefromtree(false));


			//Nodes are the last in the lists
			$this->assertFalse($this->ScopedNumberTree->movedown('cat-a-2', 1));
			$this->assertFalse($this->ScopedNumberTree->movedown('cat-a-1-2-3', 1));

			//Move node down
			$this->assertTrue($this->ScopedNumberTree->movedown('cat-a-1-2', 1));
			$expected = array('1 Root', '_1.1 Node', '_1.3 Node', '_1.2 Node', '__1.2.1 Node', '__1.2.2 Node', '__1.2.3 Node', '2 Root');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));

			//Move it back up
			$this->assertTrue($this->ScopedNumberTree->moveup('cat-a-1-2', 1));
			$expected = array('1 Root', '_1.1 Node', '_1.2 Node', '__1.2.1 Node', '__1.2.2 Node', '__1.2.3 Node', '_1.3 Node', '2 Root');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));

			//Remove a node to the root level
			$this->assertTrue($this->ScopedNumberTree->removefromtree('cat-a-1-2-3'));
			$expected = array('1 Root', '_1.1 Node', '_1.2 Node', '__1.2.1 Node', '__1.2.2 Node', '_1.3 Node', '2 Root', '1.2.3 Node');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));

			//Remove a node completely
			$this->assertTrue($this->ScopedNumberTree->removefromtree('cat-a-1-2-2', true));
			$expected = array('1 Root', '_1.1 Node', '_1.2 Node', '__1.2.1 Node', '_1.3 Node', '2 Root', '1.2.3 Node');
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-a'))));


			//Verify trees after deleting a bunch of new nodes
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
		}

		public function testFinds() {
			/**
			 * childcount()
			 */
			$this->assertFalse($this->ScopedNumberTree->childcount());

			//Count all children from a node
			$this->assertEqual(6, $this->ScopedNumberTree->childcount(array('id' => 'cat-a-1')));
			//Count direct children from a node
			$this->assertEqual(3, $this->ScopedNumberTree->childcount('cat-a-1', true));

			//Count all children in a scope
			$this->ScopedNumberTree->set('id', false);
			$this->assertEqual(8, $this->ScopedNumberTree->childCount(array('scope' => 'cat-a')));
			//Count all root nodes in a scope
			$this->assertEqual(2, $this->ScopedNumberTree->childCount(array('scope' => 'cat-a'), true));

			/**
			 * children()
			 */
			$this->assertFalse($this->ScopedNumberTree->children());

			//Get all the children from one scope
			$expected = array('cat-a-1', 'cat-a-1-1', 'cat-a-1-2', 'cat-a-1-2-1', 'cat-a-1-2-2', 'cat-a-1-2-3', 'cat-a-1-3', 'cat-a-2');
			$children = $this->ScopedNumberTree->children(array('scope' => 'cat-a'));

			$this->assertEqual($expected, Set::extract('/ScopedNumberTree/id', $children));

			//Get all the children under a specific node
			$expected = array('cat-a-1-2-1', 'cat-a-1-2-2', 'cat-a-1-2-3');
			$children = $this->ScopedNumberTree->children('cat-a-1-2');
			$this->assertEqual($expected, Set::extract('/ScopedNumberTree/id', $children));

			/**
			 * parentNode();
			 */
			$this->assertEqual('cat-a-1-2', current(current($this->ScopedNumberTree->getparentnode('cat-a-1-2-1', 'id'))));
			$this->ScopedNumberTree->id = 'cat-a-1-2-2';
			$this->assertEqual('cat-a-1-2', current(current($this->ScopedNumberTree->getparentnode(null, 'id'))));

			/**
			 * getpath();
			 */
			$this->ScopedNumberTree->id = false;
			$this->assertFalse($this->ScopedNumberTree->getPath());
			$this->assertFalse($this->ScopedNumberTree->getPath(false));
			$expected = array('cat-b-3', 'cat-b-3-2', 'cat-b-3-2-3');
			$this->assertEqual($expected, Set::extract('/ScopedNumberTree/id', $this->ScopedNumberTree->getpath('cat-b-3-2-3')));

			//Verify trees after doing lookups
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
		}

		public function testOther() {
			//Scope param is required to reorder a scoped tree
			$this->assertFalse($this->ScopedNumberTree->reorder());

			$tree = array('ScopedNumberTree' => array(
				array('name' => 'A'),
				array('name' => 'D'),
				array('name' => 'C'),
				array('name' => 'B', 'ScopedNumberTree' => array(
					array('name' => 'BC', 'ScopedNumberTree' => array(
						array('name' => 'BCB'),
						array('name' => 'BCA')
					)),
					array('name' => 'BA'),
					array('name' => 'BB')
				))
			));

			$expected = array('A', 'D', 'C', 'B', '_BC', '__BCB', '__BCA', '_BA', '_BB');
			$this->assertTrue($this->ScopedNumberTree->treeSave($tree, array('scope' => 'cat-letters')));
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-letters'))));

			//Reorder on name
			$expected = array('A', 'B', '_BA', '_BB', '_BC', '__BCA', '__BCB', 'C', 'D');
			$this->assertTrue($this->ScopedNumberTree->reorder(array('scope' => 'cat-letters', 'field' => 'name', 'id' => array('id' => false, 'scope' => 'cat-letters'))));
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generateTreeList(array('ScopedNumberTree.category_id' => 'cat-letters'))));

			//Verify trees after doing lookups
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-letters');
			$this->assertIdentical($validTree, true);
		}

		function testCounterCache() {
			$this->ScopedCounterNumberTree = new ScopedCounterNumberTree();

			$tree = array('ScopedCounterNumberTree' => array(
				array('name' => 'Root A', 'ScopedCounterNumberTree' => array(
					array('name' => 'Category A - 1'),
					array('name' => 'Category A - 2')
				)),
				array('name' => 'Root B'),
				array('name' => 'Root C', 'ScopedCounterNumberTree' => array(
					array('name' => 'Category C - 1'),
					array('name' => 'Category C - 2', 'ScopedCounterNumberTree' => array(
						array('name' => 'Category C - 2 - 1'),
						array('name' => 'Category C - 2 - 2')
					)),
					array('name' => 'Category C - 3', 'ScopedCounterNumberTree' => array(
						array('name' => 'Category C - 3 - 1')
					))
				))
			));
			$expected = array(
				'Root A',
				'_Category A - 1',
				'_Category A - 2',
				'Root B',
				'Root C',
				'_Category C - 1',
				'_Category C - 2',
				'__Category C - 2 - 1',
				'__Category C - 2 - 2',
				'_Category C - 3',
				'__Category C - 3 - 1'
			);
			$this->assertTrue($this->ScopedCounterNumberTree->treeSave($tree, array('scope' => 'test-cat')));
			$this->assertEqual($expected, array_values($this->ScopedCounterNumberTree->generateTreeList(array('ScopedCounterNumberTree.category_id' => 'test-cat'))));

			$children = $this->ScopedCounterNumberTree->children(array('id' => false, 'scope' => 'test-cat'), false, array('id', 'name', 'children_count', 'direct_children_count'));
			$childrenIds = Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/id');

			//Check children counts
			$expected = array(
				'Root A' => 2,
				'Category A - 1' => 0,
				'Category A - 2' => 0,
				'Root B' => 0,
				'Root C' => 6,
				'Category C - 1' => 0,
				'Category C - 2' => 2,
				'Category C - 2 - 1' => 0,
				'Category C - 2 - 2' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0
			);
			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/children_count'));

			//Check direct children counts
			$expected = array(
				'Root A' => 2,
				'Category A - 1' => 0,
				'Category A - 2' => 0,
				'Root B' => 0,
				'Root C' => 3,
				'Category C - 1' => 0,
				'Category C - 2' => 2,
				'Category C - 2 - 1' => 0,
				'Category C - 2 - 2' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0
			);
			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/direct_children_count'));

			$validTree = $this->ScopedCounterNumberTree->verify('test-cat');
			$this->assertIdentical($validTree, true);

			/**
			 * Adding nodes
			 */

			$data = array('name' => 'Root D', 'category_id' => 'test-cat');
			$this->ScopedCounterNumberTree->create();
			$this->assertTrue($this->ScopedCounterNumberTree->save($data));

			$data = array('name' => 'Category B - 1', 'parent_id' => $childrenIds['Root B']);
			$this->ScopedCounterNumberTree->create();
			$this->assertTrue($this->ScopedCounterNumberTree->save($data));

			$data = array('name' => 'Category C - 2 - 2 - 1', 'parent_id' => $childrenIds['Category C - 2 - 2']);;
			$this->ScopedCounterNumberTree->create();
			$this->assertTrue($this->ScopedCounterNumberTree->save($data));

			$validTree = $this->ScopedCounterNumberTree->verify('test-cat');
			$this->assertIdentical($validTree, true);

			$children = $this->ScopedCounterNumberTree->children(array('id' => false, 'scope' => 'test-cat'), false, array('id', 'name', 'children_count', 'direct_children_count'));
			$childrenIds = Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/id');

			//Check children counts
			$expected = array(
				'Root A' => 2,
				'Category A - 1' => 0,
				'Category A - 2' => 0,
				'Root B' => 1,
				'Category B - 1' => 0,
				'Root C' => 7,
				'Category C - 1' => 0,
				'Category C - 2' => 3,
				'Category C - 2 - 1' => 0,
				'Category C - 2 - 2' => 1,
				'Category C - 2 - 2 - 1' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0,
				'Root D' => 0
			);
			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/children_count'));

			//Check direct children counts
			$expected = array(
				'Root A' => 2,
				'Category A - 1' => 0,
				'Category A - 2' => 0,
				'Root B' => 1,
				'Category B - 1' => 0,
				'Root C' => 3,
				'Category C - 1' => 0,
				'Category C - 2' => 2,
				'Category C - 2 - 1' => 0,
				'Category C - 2 - 2' => 1,
				'Category C - 2 - 2 - 1' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0,
				'Root D' => 0
			);
			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/direct_children_count'));

			/**
			 * Deleting nodes
			 */

			//Delete a leaf
			$this->assertTrue($this->ScopedCounterNumberTree->delete($childrenIds['Category A - 2']));

			//Delete a root
			$this->assertTrue($this->ScopedCounterNumberTree->delete($childrenIds['Root D']));

			//Delete an entire subtree
			$this->assertTrue($this->ScopedCounterNumberTree->delete($childrenIds['Category C - 2']));

			$children = $this->ScopedCounterNumberTree->children(array('id' => false, 'scope' => 'test-cat'), false, array('id', 'name', 'children_count', 'direct_children_count', 'lft', 'rght', 'parent_id'));
			$childrenIds = Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/id');

			//Check children counts
			$expected = array(
				'Root A' => 1,
				'Category A - 1' => 0,
				'Root B' => 1,
				'Category B - 1' => 0,
				'Root C' => 3,
				'Category C - 1' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0
			);
			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/children_count'));

			//Check direct children counts
			$expected = array(
				'Root A' => 1,
				'Category A - 1' => 0,
				'Root B' => 1,
				'Category B - 1' => 0,
				'Root C' => 2,
				'Category C - 1' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0
			);

			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/direct_children_count'));

			/**
			 * Moving nodes around
			 */

			//Move C - 3 into Root B
			$this->ScopedCounterNumberTree->id = $childrenIds['Category C - 3'];
			$this->assertTrue($this->ScopedCounterNumberTree->saveField('parent_id', $childrenIds['Root B']));

			$children = $this->ScopedCounterNumberTree->children(array('id' => false, 'scope' => 'test-cat'), false, array('id', 'name', 'children_count', 'direct_children_count', 'lft', 'rght'));
			$childrenIds = Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/id');

			//Check children counts
			$expected = array(
				'Root A' => 1,
				'Category A - 1' => 0,
				'Root B' => 3,
				'Category B - 1' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0,
				'Root C' => 1,
				'Category C - 1' => 0
			);
			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/children_count'));

			//Check direct children counts
			$expected = array(
				'Root A' => 1,
				'Category A - 1' => 0,
				'Root B' => 2,
				'Category B - 1' => 0,
				'Category C - 3' => 1,
				'Category C - 3 - 1' => 0,
				'Root C' => 1,
				'Category C - 1' => 0
			);

			$this->assertEqual($expected, Set::combine($children, '/ScopedCounterNumberTree/name', '/ScopedCounterNumberTree/direct_children_count'));

			$validTree = $this->ScopedNumberTree->verify('test-cat');
			$this->assertIdentical($validTree, true);
		}
	}