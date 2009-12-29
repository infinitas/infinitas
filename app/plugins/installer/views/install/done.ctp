<div class="install">
    <h2><?php __( 'And were done' ); ?></h2>
    <p>
        Frontend:
        <?php
            echo $html->link(
                Router::url( '/', true),
                Router::url( '/', true ),
                array(
                    'target' => '_blank'
                )
            );
        ?><br />
        Admin panel:
        <?php
            echo $html->link(
                Router::url( '/admin', true),
                Router::url( '/admin', true ),
                array(
                    'target' => '_blank'
                )
            );
        ?><br />
        Username: <b>admin</b><br />
        Password: <b>password</b>
    </p>
    <p>&nbsp;</p>
    <?php
        if ( is_dir( APP.'plugins'.DS.'installer' ) )
        {
            ?>
                <p>
                    <?php echo sprintf( __( 'For security reasons it is best to rename the installation directory %s', true ), '<strong>/app/plugins/install</strong>' ); ?>
                </p>
                <p>&nbsp;</p>
            <?php

            echo $this->Html->link(
                __( 'Click here to rename the installation directory now', true ),
                array(
                    'action' => 'done',
                    'rename' => 1,
                )
            );
        }
    ?>
</div>