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

    echo $this->Form->create('Supplier', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'edit',
            'copy',
            'toggle',
            'delete'
        )
    );
    echo $this->Infinitas->adminIndexHead($this, $filterOptions, $massActions);
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
                    __('Logo', true) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('phone') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('fax') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('product_count') => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort('terms') => array(
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

            foreach ($suppliers as $supplier){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($supplier['Supplier']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/shop/supplier/'.$supplier['Supplier']['logo'],
									array(
										'height' => '35px;'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($supplier['Supplier']['name'], array('action' => 'edit', $supplier['Supplier']['id'])); ?>&nbsp;
                		</td>
						<td>
							<?php echo $supplier['Supplier']['phone']; ?>
						</td>
						<td>
							<?php echo $supplier['Supplier']['fax']; ?>
						</td>
						<td>
							<?php echo $supplier['Supplier']['product_count']; ?>
						</td>
						<td>
							<?php echo Inflector::humanize($supplier['Supplier']['terms']); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($supplier['Supplier']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($supplier['Supplier']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>