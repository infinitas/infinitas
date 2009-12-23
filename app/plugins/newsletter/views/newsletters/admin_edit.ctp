<?php
    echo $this->Form->create( 'Newsletter' );
        echo $this->Form->input( 'id' );
        echo $this->Form->input( 'campaign_id' );
        echo $this->Form->input( 'from', array( 'class' => 'title' ) );
        echo $this->Form->input( 'reply_to', array( 'class' => 'title' ) );

        echo $this->Form->input( 'subject', array( 'class' => 'title' ) );

        echo $this->Form->input( 'html' );
        echo $this->Fck->load( 'Newsletter/html', 'EmailHtml' );

        echo $this->Form->input( 'text' );
        echo $this->Fck->load( 'Newsletter/text', 'EmailText' );
    echo $this->Form->end( 'Save Newsletter' );
?>