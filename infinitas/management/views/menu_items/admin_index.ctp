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

    echo $this->Form->create( 'MenuItem', array( 'url' => array( 'controller' => 'menuItems', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'toggle',
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
                    $this->Paginator->sort( 'name' ),
                    $this->Paginator->sort( 'Menu' ) => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort( 'Access', 'Group.name' ) => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort( 'Status', 'active' ) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            $i = 0;
            foreach ( $menuItems as $menuItem )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $menuItem['MenuItem']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php
                				$paths = ClassRegistry::init('Management.MenuItem')->getPath($menuItem['MenuItem']['id']);
                				$links = array();

                				if (count($paths) > 1) {
                					echo '<b>', str_repeat('- ', count($paths)-1), ' |</b> ';
                				}

	                			echo $this->Html->link(
                					Inflector::humanize($menuItem['MenuItem']['name']),
                					array('action' => 'edit', $menuItem['MenuItem']['id'])
	                			);
                			?>&nbsp;
                		</td>
                		<td>
                			<?php echo Inflector::humanize($menuItem['Menu']['name']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo Inflector::humanize($menuItem['Group']['name']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status($menuItem['MenuItem']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>