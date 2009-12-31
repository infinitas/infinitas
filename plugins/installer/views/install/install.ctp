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
<div class="install form">
    <h2><?php __( 'Database Installation' ); ?></h2>
    <p><?php echo __( 'If you would like to install some sample data to see the power of infinitas, click the checkbox.', true ); ?></p>
    <?php
        echo $form->create( 'Install', array( 'url' => array( 'plugin' => 'installer', 'controller' => 'install', 'action' => 'install' ) ) );
        echo $form->input( 'Install.sample_data', array( 'type' => 'checkbox', 'checked' => 'true' ) );
        echo $form->end( 'Install' );
    ?>
</div>