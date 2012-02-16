<?php
    /**
     * global content
	 *
	 * Almost everything has a title and a description of some sort, so this
	 * makes things a bit simpler. Here you can do some basic management.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       Infinitas.contents
     * @subpackage    Infinitas.contents.views.global_content.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.8a
     */

    echo $this->Form->create('GlobalContent', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(array('edit'));
		echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
    <table class ="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Paginator->sort('title', __d('contents', 'Original')) => array('colspan' => 2),
                    $this->Paginator->sort('GlobalContentDuplicate.title', __d('contents', 'Duplicate')) => array('colspan' => 2),
					
					__d('contents', 'Duplicate Data'),
                    $this->Paginator->sort('modified') => array('style' => 'width:100px;')
                )
            );

            foreach ($contents as $content) {
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Infinitas->massActionCheckBox($content); ?>&nbsp;</td>
                		<td title="<?php echo __d('contents', 'Model Info :: '), $content['GlobalContent']['model']; ?>">
                			<?php echo $content['GlobalContent']['title']; ?>&nbsp;
                		</td>
                        <td><?php echo $this->Infinitas->massActionCheckBox($content, array('model' => 'GlobalContentDuplicate')); ?>&nbsp;</td>
                		<td title="<?php echo __d('contents', 'Model Info :: '), $content['GlobalContentDuplicate']['model']; ?>">
                			<?php echo $content['GlobalContentDuplicate']['title']; ?>&nbsp;
                		</td>
                		<td>
                			<?php
								$missing = array();
								$data = __d('contents', 'Duplicate :: ');
								if($content['GlobalContent']['meta_keywords'] == $content['GlobalContentDuplicate']['meta_keywords']) {
									$data .= sprintf(
										'<b>%s</b><br/>%s',
										__d('contents', 'Keywords'),
										$content['GlobalContent']['meta_keywords'] ? $content['GlobalContent']['meta_keywords'] : __d('contents', '<i>NULL</i>')
									);
									$missing[] = __d('contents', 'Keywords');
								}

								if($content['GlobalContent']['meta_description'] == $content['GlobalContentDuplicate']['meta_description']) {
									$data .= sprintf(
										'<b>%s</b><br/>%s',
										__d('contents', 'Description'),
										$content['GlobalContent']['meta_description'] ? $content['GlobalContent']['meta_description'] : __d('contents', '<i>NULL</i>')
									);
									$missing[] = __d('contents', 'Description');
								}
								
								echo $this->Text->toList($missing);
								echo sprintf('<span title="%s"> ( ? ) </span>', $data);
							?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($content['GlobalContent']['modified']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>