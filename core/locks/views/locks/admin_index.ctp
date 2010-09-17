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

    echo $this->Form->create('Lock', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'unlock'
            )
        );
	echo $this->Infinitas->adminIndexHead(null, $massActions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
					$this->Paginator->sort('User', 'User.username'),
					$this->Paginator->sort('class'),
					$this->Paginator->sort('foreign_key'),
					$this->Paginator->sort('created'),
                )
            );

            foreach ($locks as $lock){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($lock['Lock']['id']); ?>&nbsp;</td>
                		<td>
							<?php
								echo $this->Html->link(
									$lock['Locker']['username'],
									array(
										'plugin' => 'users',
										'controller' => 'users',
										'action' => 'edit',
										$lock['Locker']['id']
									)
								);
							?>&nbsp;
						</td>
                		<td><?php echo $lock['Lock']['class']; ?>&nbsp;</td>
                		<td><?php echo $lock['Lock']['foreign_key']; ?>&nbsp;</td>
                		<td><?php echo $this->Time->timeAgoInWords($lock['Lock']['created']); ?>&nbsp;</td>
                	</tr>
                <?php
            }
        ?>
    </table>
	<div style="display:none"><?php echo $this->Form->checkbox('Lock.0.id', array('checked' => true)); ?></div>
    <?php echo $this->Form->end(); ?>
</div>