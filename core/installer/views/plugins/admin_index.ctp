<?php
    /**
	 * manage the plugins on the site
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

    echo $this->Form->create('Plugin', array('action' => 'mass'));

        $massActions = $this->Infinitas->massActionButtons(
            array(
                'install',
                'uninstall',
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
                    $this->Paginator->sort('name'),
                    $this->Paginator->sort('author'),
                    $this->Paginator->sort('license'),
                    $this->Paginator->sort('dependancies'),
                    $this->Paginator->sort('version'),
                    $this->Paginator->sort('enabled'),
                    $this->Paginator->sort('core'),
                    $this->Paginator->sort('Installed', 'created'),
                    $this->Paginator->sort('Updated', 'modified'),
                )
            );

            foreach ($plugins as $plugin){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $plugin['Plugin']['id'] ); ?>&nbsp;</td>
                		<td><?php echo $plugin['Plugin']['name']; ?>&nbsp;</td>
                		<td><?php echo $this->Html->link($plugin['Plugin']['author'], $plugin['Plugin']['website']); ?>&nbsp;</td>
                		<td><?php echo $plugin['Plugin']['license']; ?>&nbsp;</td>
                		<td><?php echo $plugin['Plugin']['dependancies']; ?>&nbsp;</td>
                		<td><?php echo $plugin['Plugin']['version']; ?>&nbsp;</td>
                		<td><?php echo $this->Infinitas->status($plugin['Plugin']['enabled']); ?>&nbsp;</td>
                		<td><?php echo $this->Infinitas->status($plugin['Plugin']['core']); ?>&nbsp;</td>
                		<td><?php echo $this->Time->timeAgoInWords($plugin['Plugin']['created']); ?>&nbsp;</td>
                		<td>
							<?php
								if($plugin['Plugin']['created'] == $plugin['Plugin']['modified']){
									echo __('Never', true);
								}
								else{
									echo $this->Time->timeAgoInWords($plugin['Plugin']['created']);
								}
							?>&nbsp;
						</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>