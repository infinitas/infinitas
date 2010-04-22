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

    echo $this->Form->create('Spotlight', array('url' => array('action' => 'mass')));

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
                    $this->Paginator->sort('Product', 'Product.price'),
                    $this->Paginator->sort('start_date'),
                    $this->Paginator->sort('end_date'),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($spotlights as $spotlight){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($spotlight['Spotlight']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/shop/global/'.!empty($spotlight['Image']['image']) ? $spotlight['Image']['image'] : $spotlight['Product']['Image']['image'],
									array(
										'height' => '35px'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($spotlight['Product']['name'], array('action' => 'edit', $spotlight['Spotlight']['id'])); ?>&nbsp;
                		</td>
						<td title="<?php echo $this->Shop->breakdown($spotlight['Product']); ?>">
							<?php echo $this->Shop->currency($spotlight['Product']['price']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($spotlight['Spotlight']['start_date'].' '.$spotlight['Spotlight']['start_time']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($spotlight['Spotlight']['end_date'].' '.$spotlight['Spotlight']['end_time']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($spotlight['Spotlight']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($spotlight['Spotlight']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>