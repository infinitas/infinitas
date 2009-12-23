<?php
    echo $this->Letter->adminOtherHead( $this );
    echo $this->Form->create( 'Campaign' );
        echo $this->Form->input( 'name', array( 'class' => 'title' ) );
        echo $this->Form->input( 'description', array( 'class' => 'title' ) );
        echo $this->Form->input( 'template_id', array( 'class' => 'title' ) );
    echo $this->Form->end( __( 'Save Campaign', true ) );
?>