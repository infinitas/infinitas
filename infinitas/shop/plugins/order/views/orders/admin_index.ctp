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

    echo $this->Infinitas->adminIndexHead($this, $paginator, $filterOptions);
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
                        <td><?php echo $this->Form->checkbox($cart['Order']['id']); ?>&nbsp;</td>
						<td>
							<?php
								echo $this->Html->link(
									$cart['User']['username'],
									array(
										'plugin' => 'order',
										'controller' => 'clients',
										'action' => 'view',
										$cart['User']['id']
									)
								);
							?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['payment_method']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['shipping_method']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['tracking_number']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['item_count']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Status']['name']); ?>
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