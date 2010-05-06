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

    echo $this->Form->create('Cart', array('url' => array('action' => 'mass')));

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
                    $this->Paginator->sort('quantity'),
                    $this->Paginator->sort('price'),
                    $this->Paginator->sort('sub_total'),
                    $this->Paginator->sort('created') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('deleted_date') => array(
                        'style' => 'width:75px;'
                    )
                )
            );

            $i = 0;
            foreach ($carts as $cart){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($cart['Cart']['id']); ?>&nbsp;</td>
						<td>
							<?php echo $cart['User']['name']; ?>
						</td>
						<td>
							<?php echo $cart['Product']['name']; ?>
						</td>
						<td>
							<?php echo $cart['Cart']['quantity']; ?>
						</td>
						<td>
							<?php echo $this->Shop->currency($cart['Cart']['price']); ?>
						</td>
						<td>
							<?php echo $this->Shop->currency($cart['Cart']['sub_total']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($cart['Cart']['created']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($cart['Cart']['deleted_date']); ?>
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>