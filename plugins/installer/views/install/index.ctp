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
                            <li class="<?php echo strtolower( $option['value'] ); ?>">
                                <a href="#" title="<?php echo $option['desc']; ?>"><?php echo __( $option['value'], true ); ?></a>
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
                            <li class="<?php echo strtolower( $path['writeable'] ); ?>">
                                <a href="#" title="<?php echo $path['desc']; ?>"><?php echo __( $path['writeable'], true ); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php
            }
        ?>
    </ul>
    <h2><?php __( 'Recomended Setup' ); ?></h2>
    <ul>
        <?php
            foreach( $recomendations as $recomended )
            {
                ?>
                    <li>
                        <?php echo __( Inflector::humanize( $recomended['setting'] ), true ); ?>
                        <ul>
                            <li>
                                <?php echo __( 'Recomended', true ), ': ', __( $recomended['recomendation'], true ); ?>
                            </li>
                            <li class="<?php echo strtolower( $recomended['state'] ); ?>">
                                <?php echo __( 'Correct', true ) ?>: <a href="#" title="<?php echo $recomended['desc']; ?>"><?php echo __( $recomended['state'], true ); ?></a>
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
                    'action' => 'licence'
                )
            );
        }
        else
        {
            echo $this->Html->link(
                __( 'Fix the errors above and click here to refreash.', true ),
                array(
                    'action' => 'index'
                )
            );
        }
    ?>

</div>