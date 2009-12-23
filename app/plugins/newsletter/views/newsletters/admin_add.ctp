<?php
    echo $this->Form->create( 'Newsletter' );
        echo $this->Form->input( 'campaign_id' );
        echo $this->Form->input( 'from', array( 'class' => 'title' ) );
        echo $this->Form->input( 'reply_to', array( 'class' => 'title' ) );
        echo $this->Form->input( 'subject', array( 'class' => 'title' ) );
        echo $this->Letter->wysiwyg( 'Newsletter.html', 'EmailHtml' );
        echo $this->Letter->wysiwyg( 'Newsletter.text', 'EmailText' );
    echo $this->Form->end( 'Save Newsletter' );
?>