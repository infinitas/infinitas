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

    echo $this->Form->create('Payment', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'delete'
        )
    );
    echo $this->Infinitas->adminIndexHead($this, $filterOptions, $massActions);
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
                    $this->Paginator->sort('User', 'User.firstname'),
                    $this->Paginator->sort('order_id') => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort('amount') => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort('created') => array(
                        'style' => 'width:125px;'
                    ),
                )
            );

            foreach ($payments as $payment){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($payment['Payment']['id']); ?>&nbsp;</td>
						<td>
							<?php
								echo $this->Html->link(
									$payment['User']['name'].' '.$payment['User']['lastname'],
									array(
										'plugin' => 'management',
										'controller' => 'users',
										'action' => 'edit',
										$payment['Payment']['user_id']
									)
								);
							?>
						</td>
                        <td>
							<?php
								echo $this->Html->link(
									$this->Shop->orderNumber($payment['Payment']['order_id']),
									array(
										'plugin' => 'order',
										'controller' => 'orders',
										'action' => 'index',
										$payment['Payment']['order_id']
									)
								);
							?>&nbsp;
						</td>
                        <td>
							<?php echo $this->Shop->currency($payment['Payment']['amount']); ?>&nbsp;
						</td>
						<td>
							<?php echo $this->Time->niceShort($payment['Payment']['created']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>