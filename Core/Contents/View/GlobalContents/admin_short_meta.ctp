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
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort('model'),
                    $this->Paginator->sort('title'),
					__d('contents', 'Short Data'),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            foreach ($contents as $content) {
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Infinitas->massActionCheckBox($content); ?>&nbsp;</td>
                		<td>
                			<?php echo $content['GlobalContent']['model']; ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $content['GlobalContent']['title']; ?>&nbsp;
                		</td>
                		<td>
                			<?php 
								$missing = array();
								if(strlen($content['GlobalContent']['meta_keywords']) < 30) {
									$missing[] = __d('contents', 'Keywords');
								}
								if(strlen($content['GlobalContent']['meta_description']) < 30) {
									$missing[] = __d('contents', 'Description');
								}
								echo $this->Text->toList($missing);
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