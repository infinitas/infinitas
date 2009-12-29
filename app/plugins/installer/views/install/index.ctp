<div class="install index">
    <h2><?php __( 'Server Setup' ); ?></h2>
    <ul>
        <?php
            $continue = true;

            foreach( $setup as $option )
            {
                if ( $option['value'] == 'No' )
                {
                    $continue = false;
                }

                ?>
                    <li>
                        <?php echo $option['label']; ?>
                        <ul>
                            <li>
                                <?php echo __( $option['value'], true ); ?>
                            </li>
                        </ul>
                    </li>
                <?php
            }
        ?>
    </ul>
    <h2><?php __( 'Paths' ); ?></h2>
    <ul>
        <?php
            foreach( $paths as $path )
            {
                if ( $path['writeable'] == 'No' )
                {
                    $continue = false;
                }

                ?>
                    <li>
                        <?php echo $path['path']; ?>
                        <ul>
                            <li>
                                <?php echo __( $path['writeable'], true ); ?>
                            </li>
                        </ul>
                    </li>
                <?php
            }
        ?>
    </ul>
    <?php
        if ( $continue )
        {
            echo $this->Html->link(
                'Click here to continue',
                array(
                    'action' => 'database'
                )
            );
        }
        else
        {
            echo $this->Html->link(
                __( 'Fix the errors above and click here to refreash.' ),
                array(
                    'action' => 'index'
                )
            );
        }
    ?>

</div>