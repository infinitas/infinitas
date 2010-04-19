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

    echo $this->Form->create('ShopBranch', array('url' => array('controller' => 'branches', 'action' => 'mass', 'admin' => 'true')));

    $massActions = $this->Infinitas->massActionButtons(
        array(
            'add',
            'edit',
            'copy',
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
                    $this->Paginator->sort(__('Image', true), 'BranchDetail.image') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort(__('Name', true), 'BranchDetail.name'),
                    $this->Paginator->sort(__('Name', true), 'BranchDetail.phone') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort(__('Fax', true), 'BranchDetail.fax') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('ordering') => array(
                        'style' => 'width:50px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:75px;'
                    ),
                    $this->Paginator->sort('active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            $i = 0;
            foreach ($branches as $branch){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($branch['ShopBranch']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->image(
									'content/contact/branch/'.$branch['BranchDetail']['image'],
									array(
										'height' => '35px;'
									)
								);
							?>&nbsp;
						</td>
                		<td>
                			<?php echo $this->Html->link($branch['BranchDetail']['name'], array('action' => 'edit', $branch['ShopBranch']['id'])); ?>&nbsp;
                		</td>
						<td>
							<?php echo $branch['BranchDetail']['phone']; ?>
						</td>
						<td>
							<?php echo $branch['BranchDetail']['fax']; ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->ordering($branch['ShopBranch']['id'], $branch['ShopBranch']['ordering'], 'Shop.ShopBranchh'); ?>&nbsp;
                		</td>
						<td>
							<?php echo $this->Time->niceShort($branch['ShopBranch']['modified']); ?>
						</td>
                		<td>
                			<?php echo $this->Infinitas->status($branch['ShopBranch']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>