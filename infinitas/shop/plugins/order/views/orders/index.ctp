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
?>
<h2 class="fade"><?php __('My Orders'); ?></h2>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Paginator->sort('id'),
                    $this->Paginator->sort('payment_method'),
                    $this->Paginator->sort('shipping_method'),
                    $this->Paginator->sort('tracking_number'),
                    $this->Paginator->sort(__('Items', true), 'item_count'),
                    $this->Paginator->sort('status_id'),
                    $this->Paginator->sort('created') => array(
                        'style' => 'width:100px;'
                    )
                ),
                false
            );

            foreach ($orders as $order){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
						<td>
							<?php
								echo $this->Html->link(
									'#'.str_pad($order['Order']['id'], 5, '0', STR_PAD_LEFT),
									array(
										'plugin' => 'order',
										'controller' => 'order',
										'action' => 'view',
										$order['Order']['id']
									)
								);
							?>
						</td>
						<td>
							<?php
								if(!empty($order['Order']['payment_method'])){
									echo $order['Order']['payment_method'];
								}
								else{
									echo __('Not Paid', true);
								}
							?>
						</td>
						<td>
							<?php echo Inflector::humanize($order['Order']['shipping_method']); ?>
						</td>
						<td>
							<?php
								echo !empty($order['Order']['tracking_number']) ? $order['Order']['tracking_number'] : __('Not Shipped', true);
							?>
						</td>
						<td>
							<?php echo $order['Order']['item_count']; ?>
						</td>
						<td>
							<?php echo $order['Status']['name']; ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($order['Order']['created']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
</div>
<?php echo $this->element('pagination/navigation'); ?>