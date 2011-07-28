<?php
	App::import('lib', 'libs.test/app_model_test.php');

	App::import('Helper', 'Libs.Tree');

	class ScopedNumberTree extends CakeTestModel {
		public $name = 'ScopedNumberTree';
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

		function testHelper() {
			$this->Tree = new TreeHelper();

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
	}