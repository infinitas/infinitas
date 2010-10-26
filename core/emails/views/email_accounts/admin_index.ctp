<?php
    /**
     * Management Comments admin index view file.
     *
     * this is the admin index file that displays a list of comments in the
     * admin section of the blog plugin.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.comments.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    echo $this->Form->create('EmailAccount', array('url' => array('action' => 'mass')));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'copy',
                'toggle',
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
                    $paginator->sort('name') => array(
                        'style' => '100px;'
                    ),
                    $paginator->sort('username'),
                    $paginator->sort('type') => array(
                        'width' => '50px'
                    ),
                    $paginator->sort('port') => array(
                        'width' => '50px'
                    ),
                    $paginator->sort('ssl') => array(
                        'width' => '50px'
                    ),
                    $paginator->sort('system') => array(
                        'width' => '50px'
                    )
                )
            );

            foreach($emailAccounts as $emailAccount){
                ?>
                    <tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($emailAccount['EmailAccount']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->link(
									$emailAccount['EmailAccount']['name'],
									array(
										'action' => 'edit',
										$emailAccount['EmailAccount']['id']
									)
								);
							?>&nbsp;
						</td>
                        <td><?php echo $emailAccount['EmailAccount']['username']; ?>&nbsp;</td>
                        <td><?php echo $emailAccount['EmailAccount']['type']; ?>&nbsp;</td>
                        <td><?php echo $emailAccount['EmailAccount']['port']; ?>&nbsp;</td>
                        <td><?php echo $this->Infinitas->status($emailAccount['EmailAccount']['ssl']); ?>&nbsp;</td>
                        <td><?php echo $this->Infinitas->status($emailAccount['EmailAccount']['system']); ?>&nbsp;</td>
                    </tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>