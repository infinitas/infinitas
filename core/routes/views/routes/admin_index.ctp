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

    echo $this->Form->create( 'Route', array( 'url' => array( 'controller' => 'routes', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'copy',
                'move',
                'toggle',
                'delete'
            )
        );
        echo $this->Core->adminIndexHead( $this, $filterOptions, $massActions );
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort( 'name' ),
                    __( 'Url', true ),
                    __( 'Route', true ),
                    __( 'Theme', true ) => array(
                        'style' => 'width:75px;'
                    ),
                    __( 'Order', true ) => array(
                        'style' => 'width:50px;'
                    ),
                    __( 'Core', true ) => array(
                        'style' => 'width:50px;'
                    ),
                    __( 'Active', true ) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ( $routes as $route )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $route['Route']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $route['Route']['name'], array('action' => 'edit', $route['Route']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $route['Route']['url']; ?>&nbsp;
                		</td>
                		<td>
                			<?php
	                			$prefix = ( $route['Route']['prefix'] ) ? $route['Route']['prefix'].'/' : '';
	                			$plugin = ( $route['Route']['plugin'] ) ? $route['Route']['plugin'].'/' : '';
	                			$controller = ( $route['Route']['controller'] ) ? $route['Route']['controller'].'/' : '';
	                			$action = ( $route['Route']['action'] ) ? $route['Route']['action'].'/' : 'index/';

	                			echo '/'.$prefix.$plugin.$controller.$action;
	                		?>&nbsp;
                		</td>
                		<td>
                			<?php echo Inflector::humanize($route['Theme']['name']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Core->ordering( $route['Route']['id'], $route['Route']['ordering'] ); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status( $route['Route']['core'] ); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status( $route['Route']['active'] ); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>