<div class="sections form">
    <?php
        echo $this->Cms->adminOtherHead( $this );
        echo $this->Form->create( 'Section', array( 'action' => 'edit', 'type' => 'file' ) );
    		echo $this->Form->input( 'id' );
    		echo $this->Form->input( 'title', array( 'class' => 'title' ) );
    		echo $this->Form->input( 'group_id', array( 'label' => __( 'Min Group', true ) ) );
    		echo $this->Cms->wysiwyg( 'Section.description' );
    		echo $this->Form->input( 'active' );

        echo $this->Form->end( __( 'Submit', true ) );
    ?>
</div>