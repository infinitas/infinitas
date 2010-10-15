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
     * @since         0.8a
     */

    echo $this->Form->create('BouncedMail', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'view',
                'reply',
                'forward',
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
                    $paginator->sort('from'),
                    $paginator->sort('subject'),
                    $this->Letter->hasAttachment(true) => array(
                        'class' => 'actions',
                        'width' => '20px'
                    ),
                    $paginator->sort('size'),
                    $paginator->sort('date'),
                )
            );

            foreach($bouncedMails as $bouncedMail){
				$class = $bouncedMail['BouncedMail']['unread'] === true ? 'unread' : '';
                ?>
                    <tr class="<?php echo $this->Infinitas->rowClass(), ' ', $class; ?>">
                        <td><?php echo $this->Form->checkbox($bouncedMail['BouncedMail']['id']); ?>&nbsp;</td>
                        <td>
							<?php
								echo $this->Letter->isFlagged($bouncedMail['BouncedMail']),
									$this->Html->link($bouncedMail['BouncedMail']['from']['name'], array('action' => 'view', $bouncedMail['BouncedMail']['id']));
							?>&nbsp;
						</td>
                        <td><?php echo $bouncedMail['BouncedMail']['subject']; ?>&nbsp;</td>
                        <td><?php echo $this->Letter->hasAttachment($bouncedMail['BouncedMail']); ?>&nbsp;</td>
                        <td><?php echo convert($bouncedMail['BouncedMail']['size']); ?>&nbsp;</td>
                        <td><?php echo $this->Time->niceShort($bouncedMail['BouncedMail']['created']); ?>&nbsp;</td>
                    </tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>