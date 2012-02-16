<?php
	App::import('lib', 'libs.test/app_model_test.php');

	App::import('Helper', 'Libs.Tree');

	class ScopedNumberTree extends CakeTestModel {
		public $actsAs = array('Libs.InfiniTree' => array('scopeField' => 'category_id'));
	}

	/**
	 * TreeHelperTest class
	 */
	class TreeHelperTest extends AppModelTestCase {

		public $setup = array(
			'model' => 'Libs.ScopedNumberTree',
			'fixtures' => array(
				'do' => array(
					'Libs.ScopedNumberTree'
				)
			)
		);

		var $fixtures = array('Libs.ScopedNumberTree');

		function startTest($method) {
			parent::startTest($method);
	
			$this->Tree = new TreeHelper();
		}

		function testSettings() {
			$data = array('one', 'two', 'tree');

			$this->Tree->settings(array(
				'data' => $data,
				'model' => 'SomeModel',
				'left'=> 'other_left',
				'right' => 'other_right',
				'primaryKey' => 'other_pk',
				'parent' => 'other_parent_id'
			));

			$this->assertEqual($data, $this->Tree->data);

			$expected = array('model' => 'SomeModel', 'left'=> 'other_left', 'right' => 'other_right', 'primaryKey' => 'other_pk', 'parent' => 'other_parent_id');
			$this->assertEqual($expected, $this->Tree->settings);
		}

		function testFullTree() {
			$data = $this->ScopedNumberTree->children(array('scope' => 'cat-a'));

			$this->Tree->settings(array(
				'data' => $data,
				'model' => 'ScopedNumberTree'
			));

			$this->assertEqual($data, $this->Tree->data);

			$expected = array('model' => 'ScopedNumberTree', 'left'=> 'lft', 'right' => 'rght', 'primaryKey' => 'id', 'parent' => 'parent_id');
			$this->assertEqual($expected, $this->Tree->settings);

			//Root 1
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 1, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//1.1
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//1.2
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 1, 'firstChild' => 0, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//1.2.1
			$expected = array('root' => 0, 'depth' => 2, 'hasChildren' => 0, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//1.2.2
			$expected = array('root' => 0, 'depth' => 2, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//1.2.3
			$expected = array('root' => 0, 'depth' => 2, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//1.3
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//2
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//Test resetting the data stuff
			$data = $this->ScopedNumberTree->children(array('scope' => 'cat-b'));

			$this->Tree->settings(array(
				'data' => $data,
				'model' => 'ScopedNumberTree'
			));

			$this->assertEqual($data, $this->Tree->data);

			$expected = array('model' => 'ScopedNumberTree', 'left'=> 'lft', 'right' => 'rght', 'primaryKey' => 'id', 'parent' => 'parent_id');
			$this->assertEqual($expected, $this->Tree->settings);
		}
		
		function testSubTree() {
			$data = $this->ScopedNumberTree->children('cat-b-3');

			$this->Tree->settings(array(
				'data' => $data,
				'model' => 'ScopedNumberTree'
			));

			//Root b.3.1
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 0, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//Root b.3.2
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 1, 'firstChild' => 0, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//Root b.3.2.1
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//Root b.3.2.2
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//Root b.3.2.3
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//Root b.3.3
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);
		}

		function testFilteredTree() {
			//Build conditions to get only a filtered list
			$conditions = array(
				'OR' => array(
					'ScopedNumberTree.id' => 'cat-b-2',
					array(
						'ScopedNumberTree.lft >=' => 8,
						'ScopedNumberTree.rght <=' => 15
					)
				)
			);

			$data = $this->ScopedNumberTree->find('all', array(
				'conditions' => array_merge($conditions, array('ScopedNumberTree.category_id' => 'cat-b')),
				'order' => array('ScopedNumberTree.lft' => 'asc')
			));

			$this->Tree->settings(array(
				'data' => $data,
				'model' => 'ScopedNumberTree'
			));

			//b.2
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 0, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//b.3.2
			$expected = array('root' => 1, 'depth' => 0, 'hasChildren' => 1, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//b.3.2.1
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 1, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//b.3.2.2
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 0);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);

			//b.3.2.3
			$expected = array('root' => 0, 'depth' => 1, 'hasChildren' => 0, 'firstChild' => 0, 'lastChild' => 1);
			$nodeInfo = $this->Tree->tick();
			$this->assertEqual($expected, $nodeInfo);
		}
	}