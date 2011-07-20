	<?php
	App::import('lib', 'libs.test/app_model_test.php');
	
	class ScopedNumberTree extends CakeTestModel {
		public $name = 'ScopedNumberTree';
		public $actsAs = array('Libs.InfiniTree' => array('scopeField' => 'category_id'));
	}

	class NumberTreeTest extends AppModelTestCase {
		public $setup = array(
			'model' => 'Libs.ScopedNumberTree',
			'fixtures' => array(
				'do' => array(
					'Libs.ScopedNumberTree',
				)
			)
		);
		
		public function testFixtureIntegrity() {
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
		}
		
		public function testSaveTree() {
			//Test invalid values
			$this->assertFalse($this->ScopedNumberTree->treeSave());
			$this->assertFalse($this->ScopedNumberTree->treeSave(array()));
			$this->assertFalse($this->ScopedNumberTree->treeSave(array('foo' => 'bar')));
			$this->assertFalse($this->ScopedNumberTree->treeSave(array('data' => 'field'), array('foo' => 'bar')));
			
			$data = array('ScopedNumberTree' => array(
				array(
					'name' => 'United Kingdom',
					'ScopedNumberTree' => array(
						array(
							'name' => 'Sales'
						),
						array(
							'name' => 'Marketing'
						),
						array(
							'name' => 'R&D'
						)
					)
				),
				array(
					'name' => 'Belgium',
					'ScopedNumberTree' => array(
						array(
							'name' => 'Sales'
						),
						array(
							'name' => 'Marketing'
						),
						array(
							'name' => 'R&D'
						)
					)
				)
			));
			$expected = array('United Kingdom', '_Sales', '_Marketing', '_R&D', 'Belgium', '_Sales', '_Marketing', '_R&D');
			$this->assertTrue($this->ScopedNumberTree->treeSave($data, array('scope' => 'cat-new')));
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generatetreelist(array('ScopedNumberTree.category_id' => 'cat-new'))));			
		
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
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generatetreelist(array('ScopedNumberTree.category_id' => 'cat-a'))));
			
			//Verify trees after saving a bunch of new nodes
			$validTree = $this->ScopedNumberTree->verify('cat-a');
			$this->assertIdentical($validTree, true);
			$validTree = $this->ScopedNumberTree->verify('cat-b');
			$this->assertIdentical($validTree, true);
			
			
		}
		
		public function testDeletingNodes() {
			
		}
		
		public function testFinds() {
			/**
			 * childcount()
			 */
			$this->assertFalse($this->ScopedNumberTree->childcount());
			
			//Count all children from a node
			$this->assertEqual(6, $this->ScopedNumberTree->childcount('cat-a-1'));
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
		}
	}