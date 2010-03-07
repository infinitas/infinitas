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

    echo $this->Form->create( 'Trash', array( 'url' => array( 'controller' => 'trash', 'action' => 'mass', 'admin' => 'true', 'pluginName' => $pluginName, 'modelName' => $modelName ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'restore',
            	'delete'
            )
        );
        echo $this->Core->adminIndexHead( $this, null, null, $massActions );
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
                    $this->Paginator->sort( 'title' ),
                    $this->Paginator->sort( 'deleted_date' ) => array(
                        'style' => 'width:75px;'
                    ),
                )
            );

            foreach ( $trashedItems as $trashedItem ){
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                		 <td><?php echo $this->Form->checkbox( $trashedItem[$modelName]['id'] ); ?>&nbsp;</td>
						<td>
                			<?php echo isset($trashedItem[$modelName]['title']) ? $trashedItem[$modelName]['title'] : $trashedItem[$modelName]['name']; ?>&nbsp;
                		</td>
						<td>
                			<?php echo $this->Time->nice($trashedItem[$modelName]['deleted_date']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>