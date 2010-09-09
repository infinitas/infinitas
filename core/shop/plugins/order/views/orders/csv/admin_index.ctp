<?php
    $fields = array_keys(ClassRegistry::init($this->plugin.'.'.Inflector::singularize($this->name))->_schema);
    $needed = array(Inflector::singularize($this->name) => $fields);

    $this->Csv->ignore = array();

    echo $this->Csv->output($orders, array('fields' => $fields, 'needed' => $needed));
?>