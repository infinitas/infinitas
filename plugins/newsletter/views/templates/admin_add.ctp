<?php
    echo $this->Letter->adminOtherHead( $this );
    echo $this->Form->create( 'Template' );
        echo $this->Form->input( 'name', array( 'class' => 'title' ) );
        echo $this->Letter->wysiwyg( 'Template.header' );
        echo $this->Letter->wysiwyg( 'Template.footer' );
    echo $this->Form->end( __( 'Save Template', true ) );
?>