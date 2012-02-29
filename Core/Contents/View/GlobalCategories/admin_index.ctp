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
								echo '<span class="toggle"><a href="#">+</a></span>';
								echo $this->Infinitas->massActionCheckBox($category); 
							?>&nbsp;
						</td>
                		<td>
                			<?php
                				$paths = array(); //ClassRegistry::init('Contents.GlobalCategory')->getPath($category['GlobalCategory']['id']);
                				$links = array();

                				if (count($paths) > 1) {
                					echo '<b>', str_repeat('- ', count($paths)-1), ' |</b> ';
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
							<div class="text">
								<?php 
									echo sprintf('<span>%s</span>', __d('contents', 'Body')),
									sprintf('<p>%s</p>', $this->Text->truncate(strip_tags($category['GlobalCategory']['body']), 200)); 
								?>
							</div>
							<div class="seo">
								<?php echo sprintf('<span>%s</span>', __d('contents', 'SEO')); ?>
								<table>
									<tr>
										<th><?php echo __d('contents', 'Key'); ?></th>
										<th><?php echo __d('contents', 'Value'); ?></th>
									</tr>
									<tr>
										<td><?php echo __d('contents', 'KW densitty'); ?>&nbsp;</td>
										<td><?php echo sprintf('%s %%', $category['GlobalCategory']['keyword_density']); ?>&nbsp;</td>
									</tr>
									<tr>
										<td><?php echo __d('contents', 'Word Count'); ?>&nbsp;</td>
										<td><?php echo count(explode(' ', strip_tags($category['GlobalCategory']['body']))); ?>&nbsp;</td>
									</tr>
								</table>
							</div>
							<div class="image">
								<?php
									echo sprintf('<span>%s</span>', __d('contents', 'Image')),
									$this->Html->link(
										$this->Html->image(
											$category['GlobalCategory']['content_image_path_small'],
											array(
												'width' => '150px'
											)
										),
										$category['GlobalCategory']['content_image_path_full'],
										array(
											'class' => 'thickbox',
											'escape' => false
										)
									),
									sprintf('<p>%s</p>', $category['GlobalCategory']['image']);
								?>
							</div>
							<div class="chart">
								<?php 
									echo sprintf('<span>%s</span>', __d('contents', 'Views')),
									$this->ModuleLoader->loadDirect(
										'ViewCounter.quick_view', 
										array('model' => 'Contents.GlobalCategory', 'foreignKey' => $category['GlobalCategory']['id'])
									); 
								?>
							</div>
						</td>
					</tr>
                <?php
            }
        ?>
    </table>
    <?php
        echo $this->Form->end();

    ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>