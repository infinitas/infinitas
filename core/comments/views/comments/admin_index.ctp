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

    echo $this->Form->create('Comment', array('url' => array('action' => 'mass')));
        $massActions = $this->Infinitas->massActionButtons(
            array(
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
                    $paginator->sort(__('Where', true), 'class'),
                    $paginator->sort('email') => array(
                        'style' => '50px;'
                    ),
                    $paginator->sort('created') => array(
                        'width' => '100px'
                    ),
                    $paginator->sort('points') => array(
                        'width' => '50px'
                    ),
                    __('Status', true) => array(
                        'class' => 'actions'
                    )
                )
            );

            foreach($comments as $comment){
				$rowClass = $this->Infinitas->rowClass();
                ?>
                    <tr class="<?php echo $rowClass; ?> multi-line">
                        <td rowspan="2"><?php echo $this->Form->checkbox($comment['Comment']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Html->link(
									$comment['Comment']['class'],
									array(
										'Comment.class' => $comment['Comment']['class']
									)
								);
							?>&nbsp;
						</td>
                        <td><?php echo $this->Text->autoLinkEmails($comment['Comment']['email']); ?>&nbsp;</td>
                        <td><?php echo $this->Time->timeAgoInWords($comment['Comment']['created']); ?>&nbsp;</td>
                        <td><?php echo $comment['Comment']['points']; ?>&nbsp;</td>
                        <td rowspan="2">
                            <?php
								echo $this->Infinitas->status($comment['Comment']['active'], $comment['Comment']['id']);
                            	if(!$comment['Comment']['active']){
                            		echo ' ', Inflector::humanize($comment['Comment']['status']);
                            	}
                            ?>
                        </td>
                    </tr>
					<tr class="<?php echo $rowClass; ?>">
						<td colspan="4">
							<?php echo strip_tags($comment['Comment']['comment']); ?>
						</td>
					</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>