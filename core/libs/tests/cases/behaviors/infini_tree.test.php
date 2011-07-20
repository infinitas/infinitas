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
			$this->assertTrue($this->ScopedNumberTree->treeSave($data, array('scope' => 'cat-b')));
			$this->assertEqual($expected, array_values($this->ScopedNumberTree->generatetreelist(array('ScopedNumberTree.category_id' => 'cat-b'))));			
		}
		
		public function testScopedCounts() {
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
		}
	}