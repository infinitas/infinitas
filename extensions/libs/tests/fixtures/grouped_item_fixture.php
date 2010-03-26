<?php
class GroupedItemFixture extends CakeTestFixture {
	var $name = 'GroupedItem';
	var $table = 'grouped_items';
	var $fields = array(
    'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
    'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
    'group_field' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
    'order' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'order' => 0),
    array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'order' => 1),
    array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'order' => 2),
    array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'order' => 3),
    array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'order' => 4),
    array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'order' => 0),
    array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'order' => 1),
    array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'order' => 2),
    array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'order' => 3),
    array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'order' => 4),
    array('id' => 11, 'name' => 'Group 3 Item A', 'group_field' => 3, 'order' => 0),
    array('id' => 12, 'name' => 'Group 3 Item B', 'group_field' => 3, 'order' => 1),
    array('id' => 13, 'name' => 'Group 3 Item C', 'group_field' => 3, 'order' => 2),
    array('id' => 14, 'name' => 'Group 3 Item D', 'group_field' => 3, 'order' => 3),
    array('id' => 15, 'name' => 'Group 3 Item E', 'group_field' => 3, 'order' => 4),
  );
}
?>