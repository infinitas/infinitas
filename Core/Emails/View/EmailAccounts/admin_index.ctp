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
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   blog
	 * @subpackage	blog.views.comments.admin_index
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create('EmailAccount', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'cron_toggle',
		'system_toggle',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first',
			),
			$this->Paginator->sort('name') => array(
				'style' => '100px;'
			),
			$this->Paginator->sort('username'),
			$this->Paginator->sort('type') => array(
				'width' => '50px'
			),
			$this->Paginator->sort('port') => array(
				'width' => '50px'
			),
			$this->Paginator->sort('ssl') => array(
				'width' => '50px'
			),
			$this->Paginator->sort('system') => array(
				'width' => '50px'
			),
			$this->Paginator->sort('cron') => array(
				'width' => '50px'
			)
		));

		foreach ($emailAccounts as $emailAccount) {
			?>
				<tr>
					<td><?php echo $this->Infinitas->massActionCheckBox($emailAccount); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link($emailAccount['EmailAccount']['name'], array(
								'action' => 'edit',
								$emailAccount['EmailAccount']['id']
							));
						?>&nbsp;
					</td>
					<td><?php echo $emailAccount['EmailAccount']['username']; ?>&nbsp;</td>
					<td><?php echo $emailAccount['EmailAccount']['type']; ?>&nbsp;</td>
					<td><?php echo $emailAccount['EmailAccount']['port']; ?>&nbsp;</td>
					<td><?php echo $this->Infinitas->status($emailAccount['EmailAccount']['ssl']); ?>&nbsp;</td>
					<td><?php echo $this->Infinitas->status($emailAccount['EmailAccount']['system']); ?>&nbsp;</td>
					<td><?php echo $this->Infinitas->status($emailAccount['EmailAccount']['cron']); ?>&nbsp;</td>
				</tr>
			<?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');