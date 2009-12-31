<div class="sections form">
    <?php
        echo $this->Cms->adminOtherHead( $this );
        echo $this->Form->create( 'ContentFrontpage', array( 'action' => 'edit' ) );
    		echo $this->Form->input( 'content_id' );
        echo $this->Form->end( __( 'Submit', true ) );
    ?>
</div>