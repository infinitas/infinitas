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

    echo $this->Form->create( 'Menu', array( 'url' => array( 'controller' => 'menus', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'toggle',
                'copy',
                'delete'
            )
        );
        echo $this->Core->adminIndexHead( $this, $paginator, $filterOptions, $massActions );
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
                    $this->Paginator->sort( 'type' ) => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort( 'Active Items', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'Inactive Items', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'active', true ) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ( $menus as $menu )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $menu['Menu']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( Inflector::humanize($menu['Menu']['name']), array('controller' => 'menuItems', 'action' => 'index', 'menu_id' => $menu['Menu']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $menu['Menu']['type']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $menu['Menu']['item_count']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $menu['Menu']['item_count']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status($menu['Menu']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>