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

    echo $this->Form->create('FileManager', array('url' => array('action' => 'mass')));

        $massActions = $this->Core->massActionButtons(
            array(
                'upload',
                'view',
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
            echo $this->Core->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    __('Type', true) => array(
                        'style' => 'width:25px;'
                    ),
                    __('Name', true),
                    __('Path', true),
                    __('Size', true) => array(
                        'style' => 'width:75px;'
                    ),
                    __('Owner / Group', true) => array(
                        'style' => 'width:60px;'
                    ),
                    __('Folders / Files', true) => array(
                        'style' => 'width:60px;'
                    ),
                    __('Chmod / Octal', true) => array(
                        'style' => 'width:100px;'
                    ),
                    __('Created', true) => array(
                        'style' => 'width:100px;'
                    ),
                    __('Modified', true) => array(
                        'style' => 'width:100px;'
                    ),
                    __('Accessed', true) => array(
                        'style' => 'width:100px;'
                    )
                )
            );
            ?>
            	<tr class="<?php echo $this->Core->rowClass(); ?>">
                    <td>&nbsp;</td>
                    <td><?php echo $this->Image->image( 'actions', 'arrow-left' ); ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            	</tr>
            <?php

            foreach($folders as $folder){
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox('Folder.'.$folder['Folder']['path']); ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $this->Image->findByExtention();
                            ?>
                        </td>
                		<td>
                			<?php
                			    echo $this->Html->link(
                			        $folder['Folder']['name'],
                			        array(
                    			        'action' => 'index',
                			        ) + array_merge((array)$this->params['pass'], (array)$folder['Folder']['name'])
                    			);
                    		?>
                		</td>
                        <td>
                            <?php echo $folder['Folder']['path']; ?>
                        </td>
                        <td>
                            <?php echo $this->Number->toReadableSize($folder['Folder']['size']); ?>
                        </td>
                        <td>
                            <?php echo $folder['Folder']['owner'].' / '.$folder['Folder']['group']; ?>
                        </td>
                        <td>
                            <?php echo $folder['Folder']['sub_folders'].' / '.$folder['Folder']['sub_files']; ?>
                        </td>
                        <td>
                            <?php echo $folder['Folder']['permission'].' / '.$folder['Folder']['octal']; ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort($folder['Folder']['created']); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort($folder['Folder']['modified']); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort($folder['Folder']['accessed']); ?>
                        </td>
                	</tr>
                <?php
            }

            foreach ($files as $file){
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox('File.'.$file['File']['path']); ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $this->Image->findByExtention($file['File']['extension']);
                            ?>
                        </td>
                		<td>
                			<?php
                			    echo $this->Html->link(
                			        $file['File']['name'],
                			        array(
                    			        'action' => 'view',
                			        ) + array_merge((array)$this->params['pass'], (array)$file['File']['name'])
                    			);
                    		?>
                		</td>
                        <td>
                            <?php echo $file['File']['path']; ?>
                        </td>
                        <td>
                            <?php echo $this->Number->toReadableSize($file['File']['size']); ?>
                        </td>
                        <td>
                            <?php echo $file['File']['owner'].' / '.$file['File']['group']; ?>
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                            <?php echo $file['File']['permission'].' / '.$file['File']['octal']; ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort($file['File']['created']); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort($file['File']['modified']); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort($file['File']['accessed']); ?>
                        </td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>