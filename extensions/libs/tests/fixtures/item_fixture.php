<?php
class ItemFixture extends CakeTestFixture {
	var $name = 'Item';
	var $table = 'items';
	var $fields = array(
    'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
    'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
    'order' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('id' => 1, 'name' => 'Item A', 'order' => 0),
    array('id' => 2, 'name' => 'Item B', 'order' => 1),
    array('id' => 3, 'name' => 'Item C', 'order' => 2),
    array('id' => 4, 'name' => 'Item D', 'order' => 3),
    array('id' => 5, 'name' => 'Item E', 'order' => 4),
  );
}
?>