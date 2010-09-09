<?php
	App::import('Behavior', 'Sequence');

	class Item extends CakeTestModel{
		var $name = 'Item';
		var $actsAs = array('Libs.Sequence');
	}

	class GroupedItem extends CakeTestModel{
		var $name = 'GroupedItem';
		var $actsAs = array('Libs.Sequence' => array('group_fields' => array('group_field')));
	}

	class MultiGroupedItem extends CakeTestModel{
		var $name = 'MultiGroupedItem';
		var $actsAs = array('Libs.Sequence' => array('group_fields' => array('group_field_1', 'group_field_2')));
	}

	class SequenceBehaviorTestCase extends CakeTestCase{
		var $fixtures = array('plugin.libs.item', 'plugin.libs.grouped_item', 'plugin.libs.multi_grouped_item');

		var $defaults = array(
			'order_field' => 'order',
			'group_fields' => false,
			'start_at' => 0,
			);

		function startTest()
		{
		}

		function testSetup()
		{
			$Item = new Item();
			$this->assertEqual($Item->Behaviors->Sequence->settings['Item'], $this->defaults);
			$this->assertEqual($Item->Behaviors->Sequence->orderField, $this->defaults['order_field']);
			$this->assertFalse($Item->Behaviors->Sequence->groupFields);
			$this->assertEqual($Item->order, $Item->escapeField($this->defaults['order_field']));

			$GroupedItem = new GroupedItem();
			$this->assertEqual($GroupedItem->Behaviors->Sequence->settings['GroupedItem'], array_merge($this->defaults, $GroupedItem->actsAs['Sequence']));
			$this->assertEqual($GroupedItem->Behaviors->Sequence->groupFields, $GroupedItem->actsAs['Sequence']['group_fields']);
			$expected = array($GroupedItem->actsAs['Sequence']['group_fields'][0] => $GroupedItem->escapeField($GroupedItem->actsAs['Sequence']['group_fields'][0])
				);
			$this->assertEqual($GroupedItem->Behaviors->Sequence->escapedGroupFields, $expected);

			$MultiGroupedItem = new MultiGroupedItem();
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->settings['MultiGroupedItem'], array_merge($this->defaults, $MultiGroupedItem->actsAs['Sequence']));
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->groupFields, $MultiGroupedItem->actsAs['Sequence']['group_fields']);
		}

		function testSetSaveUpdateData()
		{
			/**
			 * Testing saving a new record (order not specified) sets order to highest + 1
			 */
			$Item = new Item();
			$data = array(
				'Item' => array(
					'name' => 'Item F'
					)
				);
			$Item->data = $data;
			$Item->setSaveUpdateData();
			$data['Item']['order'] = 5;
			$this->assertEqual($Item->data, $data);

			/**
			 * Test saving new record with order specified, sets Sequence::update array
			 */
			$Item->data['Item']['order'] = 3;
			$Item->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`Item`.`order`' => '`Item`.`order` + 1'
					),
				'conditions' => array(
					'`Item`.`order` >=' => 3
					),
				);
			$this->assertEqual($Item->Behaviors->Sequence->update, $expected);

			/**
			 * Test editing record with new order
			 */
			$Item->id = 4;
			$Item->data['Item']['order'] = 1;
			$Item->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`Item`.`order`' => '`Item`.`order` + 1'
					),
				'conditions' => array(
					array('`Item`.`order` >=' => 1),
					array('`Item`.`order` <' => 3)
					),
				);
			$this->assertEqual($Item->Behaviors->Sequence->update, $expected);

			/**
			 * Testing saving a new record (order not specified) sets order to highest + 1
			 */
			$GroupedItem = new GroupedItem();
			$data = array(
				'GroupedItem' => array(
					'name' => 'Group 2 Item F',
					'group_field' => 2
					)
				);
			$GroupedItem->data = $data;
			$GroupedItem->setSaveUpdateData();
			$data['GroupedItem']['order'] = 5;
			$this->assertEqual($GroupedItem->data, $data);

			/**
			 * Test saving new record with order specified, sets Sequence::update array
			 */
			$GroupedItem->data['GroupedItem']['order'] = 3;
			$GroupedItem->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`GroupedItem`.`order`' => '`GroupedItem`.`order` + 1'
					),
				'conditions' => array(
					'`GroupedItem`.`order` >=' => 3
					),
				);
			$this->assertEqual($GroupedItem->Behaviors->Sequence->update, $expected);

			/**
			 * Test editing record with new order
			 */
			$GroupedItem = new GroupedItem;
			$GroupedItem->id = 4;
			$GroupedItem->data['GroupedItem']['order'] = 1;
			$GroupedItem->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`GroupedItem`.`order`' => '`GroupedItem`.`order` + 1'
					),
				'conditions' => array(
					array('`GroupedItem`.`order` >=' => 1),
					array('`GroupedItem`.`order` <' => 3)
					),
				);
			$this->assertEqual($GroupedItem->Behaviors->Sequence->update, $expected);

			/**
			 * Test editing group
			 */
			$GroupedItem = new GroupedItem;
			$GroupedItem->id = 2;
			$GroupedItem->data['GroupedItem']['group_field'] = 2;
			$GroupedItem->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`GroupedItem`.`order`' => '`GroupedItem`.`order` - 1'
					),
				'conditions' => array(
					'`GroupedItem`.`order` >=' => 1,
					),
				);
			$this->assertEqual($GroupedItem->Behaviors->Sequence->update, $expected);
			$expected = array(
				'GroupedItem' => array(
					'group_field' => 2,
					'order' => 5,
					)
				);
			$this->assertEqual($GroupedItem->data, $expected);

			/**
			 * Testing saving a new record (order not specified) sets order to highest + 1
			 */
			$MultiGroupedItem = new MultiGroupedItem();
			$data = array(
				'MultiGroupedItem' => array(
					'name' => 'Group1 1 Group2 1 Item F',
					'group_field_1' => 1,
					'group_field_2' => 1
					)
				);
			$MultiGroupedItem->data = $data;
			$MultiGroupedItem->setSaveUpdateData();
			$data['MultiGroupedItem']['order'] = 5;
			$this->assertEqual($MultiGroupedItem->data, $data);

			/**
			 * Test saving new record with order specified, sets Sequence::update array
			 */
			$MultiGroupedItem->data['MultiGroupedItem']['order'] = 3;
			$MultiGroupedItem->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`MultiGroupedItem`.`order`' => '`MultiGroupedItem`.`order` + 1'
					),
				'conditions' => array(
					'`MultiGroupedItem`.`order` >=' => 3
					),
				);
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->update, $expected);

			/**
			 * Test editing record with new order
			 */
			$MultiGroupedItem = new MultiGroupedItem;
			$MultiGroupedItem->id = 4;
			$MultiGroupedItem->data['MultiGroupedItem']['order'] = 1;
			$MultiGroupedItem->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`MultiGroupedItem`.`order`' => '`MultiGroupedItem`.`order` + 1'
					),
				'conditions' => array(
					array('`MultiGroupedItem`.`order` >=' => 1),
					array('`MultiGroupedItem`.`order` <' => 3)
					),
				);
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->update, $expected);

			/**
			 * Test editing group
			 */
			$MultiGroupedItem = new MultiGroupedItem;
			$MultiGroupedItem->id = 2;
			$MultiGroupedItem->data['MultiGroupedItem']['group_field_1'] = 2;
			$MultiGroupedItem->data['MultiGroupedItem']['group_field_2'] = 2;
			$MultiGroupedItem->setSaveUpdateData();
			$expected = array(
				'action' => array(
					'`MultiGroupedItem`.`order`' => '`MultiGroupedItem`.`order` - 1'
					),
				'conditions' => array(
					'`MultiGroupedItem`.`order` >=' => 1,
					),
				);
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->update, $expected);
			$expected = array(
				'MultiGroupedItem' => array(
					'group_field_1' => 2,
					'group_field_2' => 2,
					'order' => 5,
					)
				);
			$this->assertEqual($MultiGroupedItem->data, $expected);
		}

		function testGetHighestOrder()
		{
			$Item = new Item();
			$this->assertEqual($Item->getHighestOrder(), 4);

			$GroupedItem = new GroupedItem();
			$this->assertEqual($GroupedItem->getHighestOrder(), - 1);
			$this->assertEqual($GroupedItem->getHighestOrder(array('group_field' => 1)), 4);

			$MultiGroupedItem = new MultiGroupedItem();
			$this->assertEqual($MultiGroupedItem->getHighestOrder(), - 1);
			$this->assertEqual($MultiGroupedItem->getHighestOrder(array('group_field_1' => 1, 'group_field_2' => 1)), 4);
		}

		function testSetOldOrder()
		{
			$Item = new Item();
			$Item->create();
			$Item->setOldOrder();
			$this->assertNull($Item->Behaviors->Sequence->oldOrder);

			$Item->id = 1;
			$Item->setOldOrder();
			$this->assertEqual($Item->Behaviors->Sequence->oldOrder, 0);
		}

		function testSetOldGroups()
		{
			$Item = new Item();
			$Item->create();
			$Item->setOldGroups();
			$this->assertNull($Item->Behaviors->Sequence->oldGroups);
			$Item->id = 1;
			$Item->setOldGroups();
			$this->assertNull($Item->Behaviors->Sequence->oldGroups);

			$GroupedItem = new GroupedItem();
			$GroupedItem->id = 1;
			$GroupedItem->setOldGroups();
			$this->assertEqual($GroupedItem->Behaviors->Sequence->oldGroups, array('group_field' => 1));

			$MultiGroupedItem = new MultiGroupedItem();
			$MultiGroupedItem->id = 1;
			$MultiGroupedItem->setOldGroups();
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->oldGroups, array('group_field_1' => 1, 'group_field_2' => 1));
		}

		function testSetNewOrder()
		{
			$Item = new Item();
			$Item->create();
			$Item->setNewOrder();
			$this->assertNull($Item->Behaviors->Sequence->newOrder);

			$Item->data['Item']['order'] = 1;
			$Item->setNewOrder();
			$this->assertEqual($Item->Behaviors->Sequence->newOrder, 1);
		}

		function testSetNewGroups()
		{
			$GroupedItem = new GroupedItem();
			$GroupedItem->data['GroupedItem']['group_field'] = 1;
			$GroupedItem->setNewGroups();
			$this->assertEqual($GroupedItem->Behaviors->Sequence->newGroups, array('group_field' => 1));

			$MultiGroupedItem = new MultiGroupedItem();
			$MultiGroupedItem->data['MultiGroupedItem']['group_field_1'] = 1;
			$MultiGroupedItem->data['MultiGroupedItem']['group_field_2'] = 1;
			$MultiGroupedItem->setNewGroups();
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->newGroups, array('group_field_1' => 1, 'group_field_2' => 1));
		}

		function testConditionsForGroups()
		{
			$Item = new Item();
			$this->assertEqual($Item->conditionsForGroups(), array());
			$Item->id = 1;
			$Item->setOldGroups();
			$this->assertEqual($Item->conditionsForGroups(), array());

			$GroupedItem = new GroupedItem();
			$GroupedItem->setOldGroups();
			$expected = array(
				array($GroupedItem->escapeField($GroupedItem->Behaviors->Sequence->settings['GroupedItem']['group_fields'][0]) => null,
					)
				);
			$this->assertEqual($GroupedItem->conditionsForGroups(), $expected);

			$GroupedItem->id = 1;
			$GroupedItem->setOldGroups();
			$expected = array(
				array($GroupedItem->escapeField($GroupedItem->Behaviors->Sequence->settings['GroupedItem']['group_fields'][0]) => 1,
					)
				);
			$this->assertEqual($GroupedItem->conditionsForGroups(), $expected);

			$MultiGroupedItem = new MultiGroupedItem();
			$MultiGroupedItem->setOldGroups();
			$expected = array(
				array($MultiGroupedItem->escapeField($MultiGroupedItem->Behaviors->Sequence->settings['MultiGroupedItem']['group_fields'][0]) => null,
					),
				array($MultiGroupedItem->escapeField($MultiGroupedItem->Behaviors->Sequence->settings['MultiGroupedItem']['group_fields'][1]) => null,
					)
				);
			$this->assertEqual($MultiGroupedItem->conditionsForGroups(), $expected);
			$MultiGroupedItem->id = 1;
			$MultiGroupedItem->setOldGroups();
			$expected = array(
				array($MultiGroupedItem->escapeField($MultiGroupedItem->Behaviors->Sequence->settings['MultiGroupedItem']['group_fields'][0]) => 1,
					),
				array($MultiGroupedItem->escapeField($MultiGroupedItem->Behaviors->Sequence->settings['MultiGroupedItem']['group_fields'][1]) => 1,
					)
				);
			$this->assertEqual($MultiGroupedItem->conditionsForGroups(), $expected);
		}

		function testConditionsNotCurrent()
		{
			$Item = new Item();
			$Item->id = 1;
			$this->assertEqual($Item->conditionsNotCurrent(), array($Item->escapeField($Item->primaryKey) . ' <>' => $Item->id));

			$GroupedItem = new GroupedItem();
			$GroupedItem->id = 1;
			$this->assertEqual($GroupedItem->conditionsNotCurrent(), array($GroupedItem->escapeField($GroupedItem->primaryKey) . ' <>' => $GroupedItem->id));

			$MultiGroupedItem = new MultiGroupedItem();
			$MultiGroupedItem->id = 1;
			$this->assertEqual($MultiGroupedItem->conditionsNotCurrent(), array($MultiGroupedItem->escapeField($MultiGroupedItem->primaryKey) . ' <>' => $MultiGroupedItem->id));
		}

		function testSetDeleteUpdateData()
		{
			$Item = new Item();
			$Item->id = 1;
			$expected = array(
				'action' => array($Item->order => $Item->order . ' - 1'
					),
				'conditions' => array($Item->order . ' >' => 0,
					),
				);
			$Item->setDeleteUpdateData();
			$this->assertEqual($Item->Behaviors->Sequence->update, $expected);

			$GroupedItem = new GroupedItem();
			$GroupedItem->id = 1;
			$expected = array(
				'action' => array($GroupedItem->order => $GroupedItem->order . ' - 1'
					),
				'conditions' => array($GroupedItem->order . ' >' => 0,
					),
				);
			$GroupedItem->setDeleteUpdateData();
			$this->assertEqual($GroupedItem->Behaviors->Sequence->update, $expected);

			$MultiGroupedItem = new MultiGroupedItem();
			$MultiGroupedItem->id = 1;
			$expected = array(
				'action' => array($MultiGroupedItem->order => $MultiGroupedItem->order . ' - 1'
					),
				'conditions' => array($MultiGroupedItem->order . ' >' => 0,
					),
				);
			$MultiGroupedItem->setDeleteUpdateData();
			$this->assertEqual($MultiGroupedItem->Behaviors->Sequence->update, $expected);
		}

		function testUpdateAll()
		{
		}

		function tearDown()
		{
			ClassRegistry::flush();
		}
	}