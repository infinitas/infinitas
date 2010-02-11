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
                'lock'
            )
        );
        echo $this->Core->adminIndexHead( $this, null, null, $massActions );
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
                        <td><?php echo $this->Form->checkbox( $lock['table'] ); ?>&nbsp;</td>
                		<td><?php echo $lock['table']; ?>&nbsp;</td>
                		<td><?php echo $lock['plugin']; ?>&nbsp;</td>
                		<td><?php echo $lock['model']; ?>&nbsp;</td>
                		<td><?php echo $lock['locked']; ?>&nbsp;</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>