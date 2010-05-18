<?php
    $fields = array_keys(ClassRegistry::init($this->plugin.'.'.Inflector::singularize($this->name))->_schema);
    $needed = array(Inflector::singularize($this->name) => $fields);

    echo $this->Csv->output($orders, array('fields' => $fields, 'needed' => $needed));
?>