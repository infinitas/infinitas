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
<div class="install form">
    <h2><?php __( 'Database Setup' ); ?></h2>
    <p><?php echo __( 'Please make sure you have created a database already, then fill out the details below', true ); ?></p>
    <?php
        echo $form->create( 'Install', array( 'url' => array( 'plugin' => 'installer', 'controller' => 'install', 'action' => 'database' ) ) );
        echo $form->input( 'Install.host', array( 'value' => 'localhost' ) );
        echo $form->input( 'Install.login', array( 'value' => 'root' ) );
        echo $form->input( 'Install.password' );
        echo $form->input( 'Install.database', array( 'value' => 'infinitas' ) );
        echo $form->end( 'Submit' );
    ?>
</div>