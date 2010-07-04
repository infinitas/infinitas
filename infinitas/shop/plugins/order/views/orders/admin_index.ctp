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

    echo $this->Form->create('Order', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'save',
            'export'
        )
    );
    echo $this->Infinitas->adminIndexHead($this, $paginator, $filterOptions, $massActions);
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
                    $this->Paginator->sort('id'),
                    $this->Paginator->sort(__('User', true), 'User.name'),
                    $this->Paginator->sort('payment_method'),
                    $this->Paginator->sort('shipping_method'),
                    $this->Paginator->sort('tracking_number'),
                    $this->Paginator->sort(__('Items', true), 'item_count'),
                    $this->Paginator->sort('status_id'),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('created') => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            $i = 0;
            foreach ($orders as $order){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td>
                        	<?php
                        		echo $this->Form->checkbox($order['Order']['id']);
                        		echo $this->Form->hidden('Save.'.$order['Order']['id'].'.id', array('value' => $order['Order']['id']));
                        	?>&nbsp;
                        </td>
						<td>
							<?php
								echo $this->Html->link(
									'#'.str_pad($order['Order']['id'], 5, '0', STR_PAD_LEFT),
									array(
										'plugin' => 'order',
										'controller' => 'orders',
										'action' => 'view',
										$order['Order']['id']
									)
								);
							?>
						</td>
						<td>
							<?php
								echo $this->Html->link(
									$order['User']['username'],
									array(
										'plugin' => 'order',
										'controller' => 'clients',
										'action' => 'view',
										$order['User']['id']
									)
								);
							?>
						</td>
						<td>
							<?php echo $order['Order']['payment_method']; ?>
						</td>
						<td>
							<?php
								echo $this->Form->input('Save.'.$order['Order']['id'].'.shipping_method', array('value' => $order['Order']['shipping_method'], 'div' => false, 'label' => false));
							?>
						</td>
						<td>
							<?php
								echo $this->Form->input('Save.'.$order['Order']['id'].'.tracking_number', array('value' => $order['Order']['tracking_number'], 'div' => false, 'label' => false));
							?>
						</td>
						<td>
							<?php echo $order['Order']['item_count']; ?>
						</td>
						<td>
							<?php
								echo $this->Form->input('Save.'.$order['Order']['id'].'.status_id', array('options' => $filterOptions['fields']['status_id'], 'selected' => $order['Order']['status_id'], 'div' => false, 'label' => false));
							?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['modified']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['created']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('admin/pagination/navigation'); ?>