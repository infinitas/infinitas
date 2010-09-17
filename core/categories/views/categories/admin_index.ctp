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
    echo $this->Form->create( 'Category', array( 'url' => array( 'controller' => 'categories', 'action' => 'mass', 'admin' => 'true' ) ) );
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
                    $this->Paginator->sort('Group', 'Group.name') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('Items', 'content_count') => array(
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
                    __('Status', true) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            $i = 0;
            foreach ($categories as $category){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($category['Category']['id']); ?>&nbsp;</td>
                		<td>
                			<?php
                				$paths = ClassRegistry::init('Cms.Category')->getPath($category['Category']['id']);
                				$links = array();

                				if (count($paths) > 1) {
                					echo '<b>', str_repeat('- ', count($paths)-1), ' |</b> ';
                				}

	                			echo $this->Html->link(
                					Inflector::humanize($category['Category']['title']),
                					array('action' => 'edit', $category['Category']['id'])
	                			);
							?>
                		</td>
                		<td>
                			<?php
                				if(!empty($category['Group']['name'])){
                					echo $category['Group']['name'];
                				}
                				else{
                					echo __('Public', true);
                				}
                			?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $category['Category']['item_count']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $category['Category']['views']; ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($category['Category']['modified']); ?>
                		</td>
                		<td>
                			<?php echo $this->Infinitas->treeOrdering($category['Category']); ?>&nbsp;
                		</td>
                		<td>
                			<?php
                			    echo $this->Infinitas->status($category['Category']['active'], $category['Category']['id']),
                    			    $this->Locked->display($category);
                			?>
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