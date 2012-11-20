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
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

	echo $this->Form->create('IpAddress', array('url' => array('controller' => 'ip_addresses', 'action' => 'mass', 'admin' => 'true')));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'toggle',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('ip_address'),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:125px;'
			),
			$this->Paginator->sort('active', __d('management', 'Status')) => array(
				'style' => 'width:50px;'
			)
		));

		foreach ($ipAddresses as $ipAddress) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($ipAddress); ?>&nbsp;</td>
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
				<td><?php echo $this->Infinitas->date($ipAddress['IpAddress']['modified']); ?></td>
				<td><?php echo $this->Infinitas->status($ipAddress['IpAddress']['active']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');