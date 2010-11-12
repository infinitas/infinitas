<?php
    /**
     * global layouts
	 *
	 * These make it easy for you to chage the way content looks using the mustache
	 * templating language.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       Infinitas.content
     * @subpackage    Infinitas.content.views.global_layouts.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.8a
     */

    echo $this->Form->create('GlobalLayout', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'preview',
                'toggle',
                'copy',
                'export',
                'delete'
            )
        );
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
    <table class ="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('model') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('Contents', 'content_count') => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            foreach ($layouts as $layout){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($layout['GlobalLayout']['id']); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->adminQuickLink($layout['GlobalLayout']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $layout['GlobalLayout']['model_class']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $layout['GlobalLayout']['content_count']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($layout['GlobalLayout']['modified']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>