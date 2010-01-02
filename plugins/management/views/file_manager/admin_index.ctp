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
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
    echo $this->Core->adminIndexHead( $this, null, null );
?>
<div class="table">
    <?php echo $this->Core->adminTableHeadImages(); ?>
    <?php echo $this->Form->create( 'FileManager', array( 'url' => array( 'controller' => 'posts', 'action' => 'mass', 'admin' => 'true' ) ) ); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    __( 'Type', true ) => array(
                        'style' => 'width:25px;'
                    ),
                    __( 'Name', true ),
                    __( 'Path', true ),
                    __( 'Size', true ) => array(
                        'style' => 'width:75px;'
                    ),
                    __( 'Owner / Group', true ) => array(
                        'style' => 'width:60px;'
                    ),
                    __( 'Folders / Files', true ) => array(
                        'style' => 'width:60px;'
                    ),
                    __( 'Chmod / Octal', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    __( 'Created', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    __( 'Modified', true ) => array(
                        'style' => 'width:100px;'
                    ),
                    __( 'Accessed', true ) => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            foreach ( $folders as $folder )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( 'Folder.'.$folder['Folder']['path'] ); ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $this->Html->image(

                                );
                            ?>
                        </td>
                		<td>
                			<?php
                			    $path = ( !isset( $this->params['pass'][0] ) )
                			        ? ''
                			        : $this->params['pass'][0];

                			    echo $this->Html->link(
                			        $folder['Folder']['name'],
                			        array(
                    			        'action' => 'index',
                    			        $path.'-'.$folder['Folder']['name']
                			        )
                    			);
                    		?>
                		</td>
                        <td>
                            <?php echo $folder['Folder']['path']; ?>
                        </td>
                        <td>
                            <?php echo $this->Number->toReadableSize( $folder['Folder']['size'] ); ?>
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
                            <?php echo $this->Time->niceShort( $folder['Folder']['created'] ); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort( $folder['Folder']['modified'] ); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort( $folder['Folder']['accessed'] ); ?>
                        </td>
                	</tr>
                <?php
            }

            foreach ( $files as $file )
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( 'File.'.$file['File']['path'] ); ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $this->Html->image(

                                );
                            ?>
                        </td>
                		<td>
                			<?php
                			    echo $this->Html->link(
                			        $file['File']['name'],
                			        array(
                    			        'action' => 'view',
                    			        $file['File']['Path']
                			        )
                    			);
                    		?>
                		</td>
                        <td>
                            <?php echo $file['File']['path']; ?>
                        </td>
                        <td>
                            <?php echo $this->Number->toReadableSize( $file['File']['size'] ); ?>
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
                            <?php echo $this->Time->niceShort( $file['File']['created'] ); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort( $file['File']['modified'] ); ?>
                        </td>
                        <td>
                            <?php echo $this->Time->niceShort( $file['File']['accessed'] ); ?>
                        </td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php
        echo $this->Form->button( __( 'Copy', true ), array( 'value' => 'copy', 'name' => 'copy' ) );
        echo $this->Form->button( __( 'Move', true ), array( 'value' => 'move', 'name' => 'move' ) );
        echo $this->Form->button( __( 'Delete', true ), array( 'value' => 'delete', 'name' => 'delete' ) );
        echo $this->Form->end();

    ?>
</div>