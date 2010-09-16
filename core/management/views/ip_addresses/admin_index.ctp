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

    echo $this->Form->create('IpAddress', array('url' => array('controller' => 'ip_addresses', 'action' => 'mass', 'admin' => 'true')));

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'toggle',
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
                    $this->Paginator->sort('ip_address'),
                    $this->Paginator->sort('modified') => array(
                        'style' => 'width:125px;'
                    ),
                    $this->Paginator->sort('Status', 'active') => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($ipAddresses as $ipAddress)
            {
                ?>
                	<tr class="<?php echo $this->Core->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($ipAddress['IpAddress']['id']); ?>&nbsp;</td>
                		<td>
                			<?php
								echo $this->Html->link(
	                				$ipAddress['IpAddress']['ip_address'],
	                				array(
		                				'action' => 'edit',
		                				$ipAddress['IpAddress']['id']
									)
                				);
                			?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort($ipAddress['IpAddress']['modified']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status($ipAddress['IpAddress']['active']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>