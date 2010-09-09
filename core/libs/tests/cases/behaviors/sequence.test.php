<?php

class Item extends CakeTestModel {
	var $name = 'Item';
	var $actsAs = array('Libs.Sequence' => 'order');
}

class GroupedItem extends CakeTestModel {
  var $name = 'GroupedItem';
  var $actsAs = array('Libs.Sequence' => array('group_fields' => 'group_field'));
}

class MultiGroupedItem extends CakeTestModel {
  var $name = 'MultiGroupedItem';
  var $actsAs = array('Sequence.Sequence' => array('group_fields' => array('group_field_1', 'group_field_2')));
}

class SequenceBehaviorNoGroupTestCase extends CakeTestCase {

  var $fixtures = array('plugin.libs.item');

  function testDeleteFirst() {
		$Item = new Item();
    $Item->delete(1);
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 0)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 1)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 2)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 3)),
    );
		$this->assertEqual($results, $expected);
  }

  function testDeleteMiddle() {
		$Item = new Item();
    $Item->delete(3);
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 2)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 3)),
    );
		$this->assertEqual($results, $expected);
  }

  function testDeleteEnd() {
		$Item = new Item();
    $Item->delete(5);
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 2)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 3)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertOrderNotSpecified() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'name' => 'Item F'
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 2)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 3)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 4)),
      array('Item' => array('id' => 6, 'name' => 'Item F', 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertOrderSpecifiedFirst() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'name' => 'Item F',
        'order' => '0'
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 6, 'name' => 'Item F', 'order' => 0)),
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 1)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 2)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 3)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 4)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertOrderSpecifiedMiddle() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'name' => 'Item F',
        'order' => '2'
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 6, 'name' => 'Item F', 'order' => 2)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 3)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 4)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertOrderSpecifiedEnd() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'name' => 'Item F',
        'order' => '5'
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 2)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 3)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 4)),
      array('Item' => array('id' => 6, 'name' => 'Item F', 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditOrderNotSpecified() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '1',
        'name' => 'Item A - edit',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 2)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 3)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveFirstDownMiddle() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '1',
        'order' => '3',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 0)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 1)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 2)),
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 3)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveFirstDownEnd() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '1',
        'order' => '4',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 0)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 1)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 2)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 3)),
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveMiddleDown() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '3',
        'order' => '4',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 2)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 3)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveMiddleUp() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '3',
        'order' => '1',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 1)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 2)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 3)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveEndDown() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '5',
        'order' => '4',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 2)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 3)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveEndUpMiddle() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '5',
        'order' => '2',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 0)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 1)),
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 2)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 3)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditMoveEndUpFirst() {
		$Item = new Item();
    $Item->save(array(
      'Item' => array(
        'id' => '5',
        'order' => '0',
      )
    ));
    $results = $Item->find('all');
    $expected = array(
      array('Item' => array('id' => 5, 'name' => 'Item E', 'order' => 0)),
      array('Item' => array('id' => 1, 'name' => 'Item A', 'order' => 1)),
      array('Item' => array('id' => 2, 'name' => 'Item B', 'order' => 2)),
      array('Item' => array('id' => 3, 'name' => 'Item C', 'order' => 3)),
      array('Item' => array('id' => 4, 'name' => 'Item D', 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

}

class SequenceBehaviorSingleGroupTestCase extends CakeTestCase {

	var $fixtures = array('plugin.sequence.grouped_item');

  function testDelete() {
		$GroupedItem = new GroupedItem();
    $GroupedItem->delete(1);
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => array(1,2)), 'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`order`'));
    $expected = array(
      array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 0)),
      array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'order' => 1)),
      array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 2)),
      array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 3)),
      array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'order' => 0)),
      array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'order' => 1)),
      array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'order' => 2)),
      array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'order' => 3)),
      array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertWithGroupOrderSpecified() {
		$GroupedItem = new GroupedItem();
    $GroupedItem->save(array(
      'GroupedItem' => array(
        'name' => 'Group 1 Item F',
        'group_field' => '1',
        'order' => '3',
      )
    ));
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => '1')));
    $expected = array(
      array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'order' => 0)),
      array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 1)),
      array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'order' => 2)),
      array('GroupedItem' => array('id' => 16, 'name' => 'Group 1 Item F', 'group_field' => 1, 'order' => 3)),
      array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 4)),
      array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertWithGroupOrderNotSpecified() {
		$GroupedItem = new GroupedItem();
    $GroupedItem->save(array(
      'GroupedItem' => array(
        'name' => 'Group 1 Item F',
        'group_field' => '1',
      )
    ));
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => '1')));
    $expected = array(
      array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'order' => 0)),
      array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 1)),
      array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'order' => 2)),
      array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 3)),
      array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 4)),
      array('GroupedItem' => array('id' => 16, 'name' => 'Group 1 Item F', 'group_field' => 1, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertNoGroup() {
		$GroupedItem = new GroupedItem();
    $GroupedItem->save(array(
      'GroupedItem' => array(
        'name' => 'Group Null Item A',
      )
    ));
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => '1')));
    $expected = array(
      array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'order' => 0)),
      array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 1)),
      array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'order' => 2)),
      array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 3)),
      array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => null)));
    $expected = array(
      array('GroupedItem' => array('id' => 16, 'name' => 'Group Null Item A', 'group_field' => null, 'order' => 0)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditGroupOrderNotSpecified() {
		$GroupedItem = new GroupedItem();
    $GroupedItem->save(array(
      'GroupedItem' => array(
        'id' => '3',
        'group_field' => '2',
      )
    ));
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => array(1,2)), 'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`order`'));
    $expected = array(
      array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'order' => 0)),
      array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 1)),
      array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 2)),
      array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 3)),
      array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'order' => 0)),
      array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'order' => 1)),
      array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'order' => 2)),
      array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'order' => 3)),
      array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'order' => 4)),
      array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 2, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditGroupOrderSpecified() {
		$GroupedItem = new GroupedItem();
    $GroupedItem->save(array(
      'GroupedItem' => array(
        'id' => '3',
        'group_field' => '2',
        'order' => '2',
      )
    ));
    $results = $GroupedItem->find('all', array('conditions' => array('group_field' => array(1,2)), 'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`order`'));
    $expected = array(
      array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'order' => 0)),
      array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 1)),
      array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 2)),
      array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 3)),
      array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'order' => 0)),
      array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'order' => 1)),
      array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 2, 'order' => 2)),
      array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'order' => 3)),
      array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'order' => 4)),
      array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }
}

class SequenceBehaviorMultiGroupTestCase extends CakeTestCase {

	var $fixtures = array('plugin.sequence.multi_grouped_item');

  function testDelete() {
		$MultiGroupedItem = new MultiGroupedItem();
    $MultiGroupedItem->delete(1);
    $results = $MultiGroupedItem->find('all', array('conditions' => array('group_field_1' => 1, 'group_field_2' => array(1,2)), 'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`order`'));
    $expected = array(
      array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 0)),
      array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 1)),
      array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 2)),
      array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 3)),
      array('MultiGroupedItem' => array('id' => 6, 'name' => 'Group1 1 Group2 2 Item A', 'group_field_1' => 1, 'group_field_2' => 2, 'order' => 0)),
      array('MultiGroupedItem' => array('id' => 7, 'name' => 'Group1 1 Group2 2 Item B', 'group_field_1' => 1, 'group_field_2' => 2, 'order' => 1)),
      array('MultiGroupedItem' => array('id' => 8, 'name' => 'Group1 1 Group2 2 Item C', 'group_field_1' => 1, 'group_field_2' => 2, 'order' => 2)),
      array('MultiGroupedItem' => array('id' => 9, 'name' => 'Group1 1 Group2 2 Item D', 'group_field_1' => 1, 'group_field_2' => 2, 'order' => 3)),
      array('MultiGroupedItem' => array('id' => 10, 'name' => 'Group1 1 Group2 2 Item E', 'group_field_1' => 1, 'group_field_2' => 2, 'order' => 4)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertWithGroupOrderSpecified() {
		$MultiGroupedItem = new MultiGroupedItem();
    $MultiGroupedItem->save(array(
      'MultiGroupedItem' => array(
        'name' => 'Group1 1 Group2 1 Item F',
        'group_field_1' => '1',
        'group_field_2' => '1',
        'order' => '3',
      )
    ));
    $results = $MultiGroupedItem->find('all', array('conditions' => array('group_field_1' => 1, 'group_field_2' => 1)));
    $expected = array(
      array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 0)),
      array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 1)),
      array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 2)),
      array('MultiGroupedItem' => array('id' => 126, 'name' => 'Group1 1 Group2 1 Item F', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 3)),
      array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 4)),
      array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertWithGroupOrderNotSpecified() {
		$MultiGroupedItem = new MultiGroupedItem();
    $MultiGroupedItem->save(array(
      'MultiGroupedItem' => array(
        'name' => 'Group1 1 Group2 1 Item F',
        'group_field_1' => '1',
        'group_field_2' => '1',
      )
    ));
    $results = $MultiGroupedItem->find('all', array('conditions' => array('group_field_1' => 1, 'group_field_2' => 1)));
    $expected = array(
      array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 0)),
      array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 1)),
      array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 2)),
      array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 3)),
      array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 4)),
      array('MultiGroupedItem' => array('id' => 126, 'name' => 'Group1 1 Group2 1 Item F', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

  function testInsertOneGroup() {
		$MultiGroupedItem = new MultiGroupedItem();
    $MultiGroupedItem->save(array(
      'MultiGroupedItem' => array(
        'name' => 'Group1 1 Group2 Null Item A',
        'group_field_1' => '1',
      )
    ));
    $results = $MultiGroupedItem->find('count', array('conditions' => array('group_field_1' => 1)));
    $expected = 26;
		$this->assertEqual($results, $expected);
    $results = $MultiGroupedItem->find('all', array('conditions' => array('group_field_2' => null)));
    $expected = array(
      array('MultiGroupedItem' => array('id' => 126, 'name' => 'Group1 1 Group2 Null Item A', 'group_field_1' => 1, 'group_field_2' => null, 'order' => 0)),
    );
		$this->assertEqual($results, $expected);
  }

  function testEditGroupOrderNotSpecified() {
		$MultiGroupedItem = new MultiGroupedItem();
    $MultiGroupedItem->save(array(
      'MultiGroupedItem' => array(
        'id' => '3',
        'group_field_1' => '2',
        'group_field_2' => '2',
      )
    ));
    $results = $MultiGroupedItem->find('all', array('conditions' => array(
      'OR' => array(
        array(
          'group_field_1' => 1,
          'group_field_2' => 1
        ),
        array(
          'group_field_1' => 2,
          'group_field_2' => 2
        )
      )
    ), 'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`order`'));
    $expected = array(
      array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 0)),
      array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 1)),
      array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 2)),
      array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'order' => 3)),
      array('MultiGroupedItem' => array('id' => 31, 'name' => 'Group1 2 Group2 2 Item A', 'group_field_1' => 2, 'group_field_2' => 2, 'order' => 0)),
      array('MultiGroupedItem' => array('id' => 32, 'name' => 'Group1 2 Group2 2 Item B', 'group_field_1' => 2, 'group_field_2' => 2, 'order' => 1)),
      array('MultiGroupedItem' => array('id' => 33, 'name' => 'Group1 2 Group2 2 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'order' => 2)),
      array('MultiGroupedItem' => array('id' => 34, 'name' => 'Group1 2 Group2 2 Item D', 'group_field_1' => 2, 'group_field_2' => 2, 'order' => 3)),
      array('MultiGroupedItem' => array('id' => 35, 'name' => 'Group1 2 Group2 2 Item E', 'group_field_1' => 2, 'group_field_2' => 2, 'order' => 4)),
      array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'order' => 5)),
    );
		$this->assertEqual($results, $expected);
  }

}
?>