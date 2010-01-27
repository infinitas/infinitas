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
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
<div class="sections form">
    <?php
        echo $this->Cms->adminOtherHead( $this );
        echo $this->Form->create( 'Frontpage' );
    		echo $this->Form->input( 'content_id', array( 'label' => __( 'Content Item', true ), 'type' => 'select', 'options' => $contents ) );
        echo $this->Form->end( __( 'Submit', true ) );
    ?>
</div>