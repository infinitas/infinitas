<div class="sections form">
    <?php
        echo $this->Cms->adminOtherHead( $this );
        echo $this->Form->create( 'ContentFrontpage' );
    		echo $this->Form->input( 'content_id', array( 'label' => __( 'Content Item', true ), 'type' => 'select', 'options' => $contents ) );
        echo $this->Form->end( __( 'Submit', true ) );
    ?>
</div>