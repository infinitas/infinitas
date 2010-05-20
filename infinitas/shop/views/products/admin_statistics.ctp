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

    echo $this->Form->create('Product', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
    	array(
            'toggle',
            'delete'
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
                    __('Image', true) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('rating') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('views') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('added_to_cart') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('added_to_wishlist') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('sales') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($products as $product){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($product['Product']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/shop/global/'.$product['Image']['image'],
									array(
										'height' => '35px'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($product['Product']['name'], array('action' => 'edit', $product['Product']['id'])); ?>&nbsp;
                		</td>
						<td>
							<?php echo $product['Product']['rating']; ?>
						</td>
						<td>
							<?php echo $product['Product']['views']; ?>
						</td>
						<td>
							<?php echo $product['Product']['added_to_cart']; ?>
						</td>
						<td>
							<?php echo $product['Product']['added_to_wishlist']; ?>
						</td>
						<td>
							<?php echo $product['Product']['sales']; ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($product['Product']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($product['Product']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>