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

    echo $this->Form->create( 'Theme', array( 'url' => array( 'controller' => 'themes', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'toggle',
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
                    $this->Paginator->sort( 'licence', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'author', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'Core', true ) => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort( 'active', true ) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ( $themes as $theme )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $theme['Theme']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( Inflector::humanize($theme['Theme']['name']), array('action' => 'edit', $theme['Theme']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $theme['Theme']['licence']; ?>&nbsp;
                		</td>
                		<td>
                			<?php
                				if (!empty($theme['Theme']['url'])) {
                					echo $this->Html->link($theme['Theme']['author'], $theme['Theme']['url'], array('target' => '_blank'));
                				}
                				else
								{
									echo $theme['Theme']['author'];
								}
							?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status( $theme['Theme']['core'] ); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status( $theme['Theme']['active'] ); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>