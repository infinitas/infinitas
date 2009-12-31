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