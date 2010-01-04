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

    echo $this->Form->create( 'Config', array( 'url' => array( 'controller' => 'configs', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'upload',
                'view',
                'edit',
                'copy',
                'delete'
            )
        );
        echo $this->Core->adminIndexHead( $this, $paginator, null, $massActions );
?>
<div class="table">
    <?php echo $this->Core->adminTableHeadImages(); ?>
    <?php  ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort( 'key' ),
                    $this->Paginator->sort( 'value' ) => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort( 'type' ) => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort( 'options' ) => array(
                        'style' => 'width:100px;'
                    ),
                    __( 'Description', true ),
                    __( 'Core', true ) => array(
                        'style' => 'width:50px;'
                    ),
                    __( 'Actions', true ) => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            $i = 0;
            foreach ( $configs as $config )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $config['Config']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $config['Config']['key'], array('controller' => 'configs', 'action' => 'edit', $config['Config']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $config['Config']['value']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo Inflector::humanize( $config['Config']['type'] ); ?>&nbsp;
                		</td>
                		<td>
                			<?php
                			    if ( !empty( $config['Config']['options'] ) )
                			    {
                			        echo $this->Text->toList( explode( ',', Inflector::humanize( $config['Config']['options'] ) ), 'or' );
                			    }
                			?>&nbsp;
                		</td>
                		<td>
                			<?php echo $config['Config']['description']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Status->status( $config['Config']['core'] ); ?>&nbsp;
                		</td>
                		<td class="actions">
                			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $config['Config']['id'])); ?>
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php
        echo $this->Form->end();

    ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>