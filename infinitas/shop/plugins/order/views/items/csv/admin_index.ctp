<?php
    $fields = array_keys(ClassRegistry::init($this->plugin.'.'.Inflector::singularize($this->name))->_schema);
    $needed = array(Inflector::singularize($this->name) => $fields);

    $this->Csv->ignore = array('id', 'product_id');

    echo $this->Csv->output($items, array('fields' => $fields, 'needed' => $needed));
?>