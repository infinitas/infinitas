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

    echo $this->Core->adminIndexHead( $this);
?>
<div class="table">
    <?php echo $this->Core->adminTableHeadImages(); ?>
    <?php  ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
                    __('Table', true) => array(
                        'style' => 'width:75px;'
                    ),
                    __('Plugin', true) => array(
                        'style' => 'width:75px;'
                    ),
                    __('Model', true) => array(
                        'style' => 'width:75px;'
                    ),
                    __('Trashed', true) => array(
                        'style' => 'width:75px;'
                    )
                )
            );

            foreach ( $trashed as $trash ){
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                		<td><?php echo $this->Html->link($trash['table'], array('action' => 'list_items', 'pluginName' => $trash['plugin'], 'modelName' => $trash['model'])); ?>&nbsp;</td>
                		<td><?php echo $trash['plugin']; ?>&nbsp;</td>
                		<td><?php echo $trash['model']; ?>&nbsp;</td>
                		<td><?php echo $trash['deleted']; ?>&nbsp;</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>