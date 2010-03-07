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

    echo $this->Form->create( 'Newsletter', array( 'url' => array( 'controller' => 'newsletters', 'action' => 'mass', 'admin' => 'true' ) ) );
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
        echo $this->Letter->adminIndexHead( $this, $paginator, $filterOptions, $massActions );
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <tr>
            <th class="first" style="width:10px;"><?php echo $this->Form->checkbox( 'all' ); ?></th>
            <th><?php echo $paginator->sort( 'subject' ); ?></th>
            <th><?php echo $paginator->sort( 'Campaign', 'Campaign.name' ); ?></th>
            <th style="width:100px;"><?php echo $paginator->sort( 'from' ); ?></th>
            <th style="width:100px;"><?php echo $paginator->sort( 'reply_to' ); ?></th>
            <th style="width:50px;"><?php echo $paginator->sort( 'status' ); ?></th>
            <th style="width:50px;"><?php echo $paginator->sort( 'sent' ); ?></th>
        </tr>
        <?php
            $i = 0;
            foreach( $newsletters as $newsletter )
            {
                ?>
                    <tr class="<?php echo $this->Letter->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $newsletter['Newsletter']['id'] ); ?>&nbsp;</td>
                        <td><?php echo $newsletter['Newsletter']['subject'] ?>&nbsp;</td>
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
                        <td><?php echo $newsletter['Newsletter']['from'] ?>&nbsp;</td>
                        <td><?php echo $newsletter['Newsletter']['reply_to'] ?>&nbsp;</td>
                        <td>
                            <?php
                                if ( $newsletter['Newsletter']['sent'] )
                                {
                                    echo $this->Html->link(
                                        $this->Html->image(
                                            'core/icons/actions/16/reports.png'
                                        ),
                                        array(
                                            'action' => 'report',
                                            $newsletter['Newsletter']['id']
                                        ),
                                        array(
                                            'title' => __( 'Sending complete. See the report.', true ),
                                            'alt' => __( 'Done', true ),
                                            'escape' => false
                                        )
                                    );
                                }
                                else
                                {
                                    echo $this->Infinitas->toggle( $newsletter['Newsletter']['active'], $newsletter['Newsletter']['id'] );
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ( $newsletter['Newsletter']['active'] && !$newsletter['Newsletter']['sent'] )
                                {
                                    echo $this->Html->image(
                                        'core/icons/actions/16/update.png',
                                        array(
                                            'alt' => __( 'In Progress', true ),
                                            'title' => __( 'Busy sending', true )
                                        )
                                    );
                                }
                                else
                                {
                                    echo $this->Letter->toggle( $newsletter['Newsletter']['id'], $newsletter['Newsletter']['sent'] );
                                }
                            ?>
                        </td>
                    </tr>
                <?php
                $i++;
            }
        ?>
    </table>
    <?php
        echo $this->Form->end();
    ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>