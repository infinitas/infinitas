<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
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