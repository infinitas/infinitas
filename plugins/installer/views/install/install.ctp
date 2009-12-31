<div class="install form">
    <h2><?php __( 'Database Installation' ); ?></h2>
    <p><?php echo __( 'If you would like to install some sample data to see the power of infinitas, click the checkbox.', true ); ?></p>
    <?php
        echo $form->create( 'Install', array( 'url' => array( 'plugin' => 'installer', 'controller' => 'install', 'action' => 'install' ) ) );
        echo $form->input( 'Install.sample_data', array( 'type' => 'checkbox', 'checked' => 'true' ) );
        echo $form->end( 'Install' );
    ?>
</div>