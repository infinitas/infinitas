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

    echo $this->Form->create( 'Trash', array( 'url' => array( 'controller' => 'trash', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'restore',
                'delete'
            )
        );
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
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
                    $this->Paginator->sort( 'Type', 'model' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'deleted' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'Deleted By', 'Deleter.name' ) => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            foreach ( $trashed as $trash )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $trash['Trash']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo Inflector::humanize($trash['Trash']['name']); ?>&nbsp;
                		</td>
                		<td>
                			<?php $type = explode('.', $trash['Trash']['model']); echo $type[0] . (isset($type[1]) ? ' ' . $type[1] : ''); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $trash['Trash']['deleted']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $trash['Trash']['deleted_by']; ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>