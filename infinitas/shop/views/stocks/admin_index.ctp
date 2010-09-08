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

    echo $this->Form->create('Stock', array('url' => array('action' => 'mass')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'edit'
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
                    $this->Paginator->sort('Branch', 'BranchDetail.name'),
                    $this->Paginator->sort('Product', 'Product.name') => array(
                        'style' => 'width:150px;'
                    ),
                    $this->Paginator->sort('stock') => array(
                        'style' => 'width:75px;'
                    )
                )
            );

            foreach ($stocks as $stock){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($stock['Stock']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->link(
									$stock['ShopBranch']['BranchDetail']['name'],
									array(
										'controller' => 'branches',
										'action' => 'edit',
										$stock['ShopBranch']['id']
									)
								);
							?>&nbsp;
						</td>
                        <td>
							<?php
								echo $this->Html->link(
									$stock['Product']['name'],
									array(
										'controller' => 'products',
										'action' => 'edit',
										$stock['Product']['id']
									)
								);
							?>&nbsp;
						</td>
                        <td>
							<?php echo $stock['Stock']['stock']; ?>&nbsp;
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>