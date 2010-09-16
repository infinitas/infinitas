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

    echo $this->Form->create('Newsletter', array('action' => 'mass'));
        $massActions = $this->Letter->massActionButtons(
            array(
                'add',
                'view',
                'edit',
                'copy',
                'toggle',
                'send',
                'delete'
            )
        );
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
    	<?php
            echo $this->Letter->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $paginator->sort('subject'),
                    $paginator->sort('Campaign', 'Campaign.name') => array(
                        'style' => 'width:150px;'
                    ),
                    $paginator->sort('from') => array(
                        'style' => 'width:100px;'
                    ),
                    $paginator->sort('reply_to') => array(
                        'style' => 'width:100px;'
                    ),
                    $paginator->sort('sent') => array(
                        'style' => 'width:100px;'
                    ),
                    __('Status', true) => array(
                        'class' => 'actions',
                        'width' => '50px'
                    )
                )
            );

            foreach($newsletters as $newsletter){
                ?>
                    <tr class="<?php echo $this->Letter->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($newsletter['Newsletter']['id']); ?>&nbsp;</td>
                        <td><?php echo $newsletter['Newsletter']['subject']; ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $html->link(
                                    $newsletter['Campaign']['name'],
                                    array(
                                        'controller' => 'campaign',
                                        'action' => 'view',
                                        $newsletter['Newsletter']['campaign_id']
                                    )
                                );
                            ?>&nbsp;
                        </td>
                        <td><?php echo $newsletter['Newsletter']['from']; ?>&nbsp;</td>
                        <td><?php echo $newsletter['Newsletter']['reply_to']; ?>&nbsp;</td>
                        <td>
                            <?php
                                if ($newsletter['Newsletter']['active'] && !$newsletter['Newsletter']['sent']){
                                    echo $this->Html->image(
                                        'core/icons/actions/16/update.png',
                                        array(
                                            'alt' => __('In Progress', true),
                                            'title' => __('Busy sending', true)
                                        )
                                    );
                                }

                                else{
                                    echo $this->Letter->toggle($newsletter['Newsletter']['id'], $newsletter['Newsletter']['sent']);
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ($newsletter['Newsletter']['sent']){
                                    echo $this->Html->link(
                                        $this->Html->image(
                                            'core/icons/actions/16/reports.png'
                                        ),
                                        array(
                                            'action' => 'report',
                                            $newsletter['Newsletter']['id']
                                        ),
                                        array(
                                            'title' => __('Sending complete. See the report.', true),
                                            'alt' => __('Done', true ),
                                            'escape' => false
                                        )
                                    );
                                }

                                else{
                                    echo $this->Letter->toggle($newsletter['Newsletter']['active'], $newsletter['Newsletter']['id']);
                                }
                            ?>
                        </td>
                    </tr>
                <?php
            }
        ?>
    </table>
    <?php
        echo $this->Form->end();
    ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>