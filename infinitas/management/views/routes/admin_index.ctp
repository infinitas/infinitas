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

    echo $this->Form->create( 'Route', array( 'url' => array( 'controller' => 'routes', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'copy'
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
                    $this->Paginator->sort( 'name' ),
                    __( 'Url', true ),
                    __( 'Route', true ),
                    __( 'Core', true ) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            $i = 0;
            foreach ( $routes as $route )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $route['Route']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $route['Route']['name'], array('action' => 'edit', $route['Route']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $route['Route']['url']; ?>&nbsp;
                		</td>
                		<td>
                			<?php
	                			$plugin = ( $route['Route']['plugin'] ) ? $route['Route']['plugin'].'/' : '';
	                			$controller = ( $route['Route']['controller'] ) ? $route['Route']['controller'].'/' : '';
	                			$action = ( $route['Route']['action'] ) ? $route['Route']['action'].'/' : '';
	                			$all = ( $route['Route']['match_all'] ) ? '*' : '';

	                			echo '/'.$plugin.$controller.$action.$all;
	                		?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Status->status( $route['Route']['core'] ); ?>&nbsp;
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