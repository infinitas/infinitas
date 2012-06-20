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
                //'preview',
                'toggle',
                'copy',
                //'export',
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
                        'style' => 'width:100px;',
						'title' => __d('contents', 'Data type :: This is the model that the layout is intended to be used with. <br/>Connecting this layout to another model may case <br/>display issues.')
                    ),
                    $this->Paginator->sort('Theme') => array(
                        'style' => 'width:100px;',
						'title' => __d('contents', 'Theme overload :: Setting a theme here will overload the theme connected <br/>to the route being displayed. Themes displayed as <br/>Global.Global will not be affected.')
                    ),
                    $this->Paginator->sort('content_count', __d('contents', 'Count')) => array(
                        'style' => 'width:100px;',
						'title' => __d('contents', 'Content count :: This displays the number of content items using this layout <br/>to display data.')
                    ),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    ),
					__d('contents', 'Status')
                )
            );

            foreach ($layouts as $layout) {
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Infinitas->massActionCheckBox($layout); ?>&nbsp;</td>
                		<td><?php echo $this->Html->adminQuickLink($layout['GlobalLayout']); ?>&nbsp;</td>
                		<td><?php echo $layout['GlobalLayout']['model_class']; ?>&nbsp;</td>
                		<td>
                			<?php
								if(!$layout['GlobalLayout']['theme_id']) {
									$layout['Theme']['name'] = __d('themes', 'Global');
								}
							
								if(!$layout['GlobalLayout']['layout']) {
									$layout['GlobalLayout']['layout'] = __d('themes', 'Global');
								}
								echo implode('<b>.</b>', array($layout['Theme']['name'], $layout['GlobalLayout']['layout']));
							?>&nbsp;
                		</td>
                		<td><?php echo $layout['GlobalLayout']['content_count']; ?>&nbsp;</td>
                		<td><?php echo $this->Time->niceShort($layout['GlobalLayout']['modified']); ?>&nbsp;</td>
                		<td><?php echo $this->Locked->display($layout); ?>&nbsp;</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>