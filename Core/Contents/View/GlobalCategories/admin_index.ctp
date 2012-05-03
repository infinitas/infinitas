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
    echo $this->Form->create('GlobalCategory', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'preview',
                'toggle',
                'copy',
                'move',
                'delete'
            )
        );
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
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
                    $this->Paginator->sort('title'),
                    $this->Paginator->sort('Group.name', __d('contents', 'Group')) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('content_count', __d('contents', 'Items')) => array(
                        'style' => 'width:35px;'
                    ),
                    $this->Paginator->sort('views') => array(
                        'style' => 'width:40px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('ordering') => array(
                        'style' => 'width:50px;'
                    ),
                    __d('contents', 'Status') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            $i = 0;
            foreach ($categories as $category) {
				$rowClass = $this->Infinitas->rowClass();
                ?>
                	<tr class="parent <?php echo $rowClass; ?>">
                        <td>
							<?php 
								echo '<span class="toggle"><a href="#">+</a></span>', 
								$this->Infinitas->massActionCheckBox($category); 
							?>&nbsp;
						</td>
                		<td>
                			<?php
                				if ($category['GlobalCategory']['path_depth'] >= 1) {
                					echo '<b>', str_repeat('- ', $category['GlobalCategory']['path_depth']), ' |</b> ';
                				}

	                			echo $this->Html->link(
                					Inflector::humanize($category['GlobalCategory']['title']),
                					array('action' => 'edit', $category['GlobalCategory']['id'])
	                			);
							?>
                		</td>
                		<td>
                			<?php
                				if(!empty($category['Group']['name'])){
                					echo $category['Group']['name'];
                				}
                				else{
                					echo __('Public');
                				}
                			?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $category['GlobalCategory']['item_count']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $category['GlobalCategory']['views']; ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($category['GlobalCategory']['modified']); ?>
                		</td>
                		<td>
                			<?php echo $this->Infinitas->treeOrdering($category['GlobalCategory']); ?>&nbsp;
                		</td>
                		<td>
                			<?php
                			    echo
									$this->Infinitas->status($category['GlobalCategory']['hide'], $category['GlobalCategory']['id']),
									$this->Infinitas->status($category['GlobalCategory']['active'], $category['GlobalCategory']['id']),
                    			    $this->Locked->display($category);
                			?>
                		</td>
                	</tr>
					<tr class="details <?php echo $rowClass; ?>">
						<td colspan="100">
							<?php 
								echo $this->element('Contents.expanded/body', array('data' => $category['GlobalCategory']));
								echo $this->element('Contents.expanded/image', array('data' => $category['GlobalCategory']));
								echo $this->element('Contents.expanded/seo', array('data' => $category['GlobalCategory']));
								echo $this->element('Contents.expanded/views', array('data' => $category['GlobalCategory'], 'model' => 'Contents.GlobalCategory'));
							?>
						</td>
					</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>