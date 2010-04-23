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

    echo $this->Form->create('Special', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'edit',
            'copy',
            'toggle',
            'move',
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
                    $this->Paginator->sort('Product', 'Product.name'),
                    $this->Paginator->sort('discount'),
                    $this->Paginator->sort('amount'),
                    __('Adjusted Price'),
                    $this->Paginator->sort('start_date') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('end_date') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($specials as $special){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($special['Special']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/shop/global/'.!empty($special['Image']['image']) ? $special['Image']['image'] : $special['Product']['Image']['image'],
									array(
										'height' => '35px'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($special['Product']['name'], array('action' => 'edit', $special['Special']['id'])); ?>&nbsp;
                		</td>
						<td>
							<?php echo $this->Number->toPercentage($special['Special']['discount']); ?>
						</td>
						<td>
							<?php echo $this->Shop->currency($special['Special']['amount']); ?>
						</td>
						<td title="<?php echo $this->Shop->breakdown($spotlight['Product'], $special['Special']); ?>">
							<?php echo $this->Shop->calculateSpecial($special['Product'], $special['Special']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($special['Special']['start_date'].' '.$special['Special']['start_time']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($special['Special']['end_date'].' '.$special['Special']['end_time']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($special['Special']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($special['Special']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>