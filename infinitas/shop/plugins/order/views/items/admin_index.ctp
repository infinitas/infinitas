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

    echo $this->Form->create('Item', array('url' => array('action' => 'mass')));
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
                    $this->Paginator->sort('order_id'),
                    $this->Paginator->sort('product_id'),
                    $this->Paginator->sort('price'),
                    $this->Paginator->sort('quantity'),
                    $this->Paginator->sort('sub_total'),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('created') => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            $i = 0;
            foreach ($items as $item){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($item['Item']['id']); ?>&nbsp;</td>
						<td>
							<?php
								echo $this->Html->link(
									'#'.str_pad($item['Order']['id'], 5, '0', STR_PAD_LEFT),
									array(
										'plugin' => 'order',
										'controller' => 'orders',
										'action' => 'view',
										$item['Order']['id']
									)
								);
							?>
						</td>
						<td>
							<?php
								if(!empty($item['Product'])){
									echo $this->Html->link(
										$item['Product']['name'],
										array(
											'plugin' => 'shop',
											'controller' => 'products',
											'action' => 'edit',
											$item['Product']['id']
										)
									);
								}
								else{
									echo sprintf('%s (%s)', $item['Item']['name'], __('Discontinued', true));
								}
							?>
						</td>
						<td>
							<?php echo $this->Shop->currency($item['Item']['price']); ?>
						</td>
						<td>
							<?php echo $item['Item']['quantity']; ?>
						</td>
						<td>
							<?php echo $this->Shop->currency($item['Item']['sub_total']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($item['Item']['modified']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($item['Item']['created']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('admin/pagination/navigation'); ?>