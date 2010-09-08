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

    echo $this->Form->create('ShopCategory', array('url' => array('action' => 'mass')));

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
                    __('Image', true) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('parent_id'),
                    __('Branches', true),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('ordering') => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort('active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($categories as $category){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($category['ShopCategory']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/shop/global/'.$category['Image']['image'],
									array(
										'height' => '35px'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($category['ShopCategory']['name'], array('action' => 'edit', $category['ShopCategory']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php
                				if(!empty($category['Parent']['name'])){
                					echo $this->Html->link($category['Parent']['name'], array('action' => 'edit', $category['Parent']['id']));
                				}
                				else{
									echo __('Root Category');
                				}
                			?>&nbsp;
                		</td>
						<td>
							<?php echo $this->Text->toList(Set::extract('/BranchDetail/name', $category['ShopBranch'])); ?>
						</td>
						<td>
							<?php echo $this->Time->niceShort($category['ShopCategory']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->treeOrdering($category['ShopCategory']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status($category['ShopCategory']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>