<?php
    echo $this->element( 'comments/add', array( 'plugin' => 'core', 'fk' => $this->data['Post']['id'] ) );
?>