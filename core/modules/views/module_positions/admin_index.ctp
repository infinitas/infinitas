<?php
	/**
	 * Module positions controller
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.modules
	 * @subpackage Infinitas.modules.controllers.module_positions
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

    echo $this->Form->create('ModulePosition', array('url' => array('action' => 'mass')));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'copy',
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
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('Modules', 'module_count') => array(
						'style' => 'width:100px'
					),
                    $this->Paginator->sort('modified') => array(
						'style' => 'width:100px'
					)
                )
            );

            $i = 0;
            foreach ($modulePositions as $modulePosition){
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($modulePosition['ModulePosition']['id']); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link(Inflector::humanize($modulePosition['ModulePosition']['name']), array('action' => 'edit', $modulePosition['ModulePosition']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $modulePosition['ModulePosition']['module_count']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($modulePosition['ModulePosition']['modified']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>