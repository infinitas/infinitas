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
?>
<div class="table checkout">
	<h2 class="fade"><?php __('Wishlist'); ?></h2>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    __('Product', true),
                    __('Price', true) => array(
                        'style' => 'width:100px;'
                    ),
                    __('Added', true) => array(
                        'style' => 'width:150px;'
                    ),
                    __('Actions', true) => array(
                        'style' => 'width:100px;'
                    )
                ),
                false
            );

            $i = 0;
            foreach ($wishlists as $wishlist){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass('even'); ?>">
						<td>
							<?php
								$wishlist['Product']['plugin'] = 'shop';
								$wishlist['Product']['controller'] = 'products';
								$wishlist['Product']['action'] = 'view';
								$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $wishlist['Product']));

								echo $this->Html->link(
									$wishlist['Product']['name'],
									current($eventData['slugUrl'])
								);
							?>&nbsp;
						</td>
						<td>
							<?php echo $this->Shop->currency($wishlist['Wishlist']['price']); ?>&nbsp;
						</td>
						<td>
							<?php echo $this->Time->timeAgoInWords($wishlist['Wishlist']['created']); ?>&nbsp;
						</td>
						<td>
							<?php echo $this->Shop->wishlistActions($wishlist); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>