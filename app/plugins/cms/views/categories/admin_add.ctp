<div class="sections form">
    <?php
        echo $this->Cms->adminOtherHead( $this );
        echo $this->Form->create( 'Category' );
    		echo $this->Form->input( 'title', array( 'class' => 'title' ) );
    		echo $this->Form->input( 'section_id' );
    		echo $this->Form->input( 'group_id', array( 'label' => __( 'Min Group', true ) ) );
    		echo $this->Cms->wysiwyg( 'Category.description' );
    		echo $this->Form->input( 'active' );
        echo $this->Form->end(__('Submit', true));
    ?>
</div>