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

    echo $this->Form->create('Wishlist', array('url' => array('action' => 'mass')));

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
                    $this->Paginator->sort(__('Product', true), 'Product.name'),
                    $this->Paginator->sort('price'),
                    $this->Paginator->sort('created') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('deleted_date') => array(
                        'style' => 'width:150px;'
                    )
                )
            );

            $i = 0;
            foreach ($wishlists as $wishlist){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($wishlist['Wishlist']['id']); ?>&nbsp;</td>
						<td>
							<?php
								echo $this->Html->link(
									$wishlist['User']['username'],
									array(
										'plugin' => 'management',
										'controller' => 'users',
										'action' => 'edit',
										$wishlist['User']['id']
									)
								);
							?>
						</td>
						<td>
							<?php
								echo $this->Html->link(
									$wishlist['Product']['name'],
									array(
										'plugin' => 'shop',
										'controller' => 'products',
										'action' => 'edit',
										$wishlist['Product']['id']
									)
								);
							?>
						</td>
						<td>
							<?php echo $this->Shop->currency($wishlist['Wishlist']['price']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($wishlist['Wishlist']['created']); ?>
						</td>
						<td>
							<?php echo $this->Time->timeAgoInWords($wishlist['Wishlist']['deleted_date']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>