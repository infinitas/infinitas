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

    echo $this->Form->create( 'Lock', array( 'url' => array( 'controller' => 'locks', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'unlock'
            )
        );
	echo $this->Infinitas->adminIndexHead(null, $massActions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
                    __('Table', true),
                    __('Plugin', true) => array(
                        'style' => 'width:75px;'
                    ),
                    __('Model', true) => array(
                        'style' => 'width:75px;'
                    ),
                    __('Locked', true) => array(
                        'style' => 'width:75px;'
                    )
                )
            );

            foreach ( $locks as $lock )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                		<td><?php echo $lock['table']; ?>&nbsp;</td>
                		<td><?php echo $lock['plugin']; ?>&nbsp;</td>
                		<td><?php echo $lock['model']; ?>&nbsp;</td>
                		<td><?php echo $lock['locked']; ?>&nbsp;</td>
                	</tr>
                <?php
            }
        ?>
    </table>
	<div style="display:none"><?php echo $this->Form->checkbox('Lock.0.id', array('checked' => true)); ?></div>
    <?php echo $this->Form->end(); ?>
</div>