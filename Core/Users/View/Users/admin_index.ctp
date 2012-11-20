<?php
	/**
	 * View for managing users
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Users.View
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	echo $this->Form->create(null, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'move',
		'toggle',
		'delete'
	));
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox('all') => array(
						'class' => 'first'
					),
					$this->Paginator->sort('username'),
					$this->Paginator->sort('email'),
					$this->Paginator->sort('browser') => array(
						'class' => 'large'
					),
					$this->Paginator->sort('operating_system', __d('users', 'OS')) => array(
						'class' => 'large'
					),
					$this->Paginator->sort('country') => array(
						'class' => 'large'
					),
					$this->Paginator->sort('birthday') => array(
						'class' => 'medium'
					),
					$this->Paginator->sort('modified') => array(
						'class' => 'date'
					),
					$this->Paginator->sort('last_login') => array(
						'class' => 'date'
					),
					__d('users', 'Active') => array(
						'class' => 'small'
					)
				)
			);

			foreach ($users as $user) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Infinitas->massActionCheckBox($user); ?>&nbsp;</td>
					<td><?php echo $this->Infinitas->adminQuickLink($user['User']); ?>&nbsp;</td>
					<td><?php echo $this->Text->autoLinkEmails($user['User']['email']); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link($user['User']['browser'], array(
								'User.browser' => preg_replace('/[^a-z]/', '', strtolower($user['User']['browser']))
							));
						?>&nbsp;
					</td>
					<td>
						<?php
							echo $this->Html->link($user['User']['operating_system'], array(
								'User.operating_system' => preg_replace('/[^a-z]/', '', strtolower($user['User']['operating_system']))
							));
						?>&nbsp;
					</td>
					<td><?php echo $user['User']['country']; ?>&nbsp;</td>
					<td>
						<?php
							$date = __d('users', 'Not Set');
							if ($user['User']['birthday']) {
								$date = $this->Infinitas->date($user['User']['birthday'], null, false);
							}
							echo $date;
						?>&nbsp;
					</td>
					<td><?php echo $this->Infinitas->date($user['User']['modified']); ?></td>
					<td><?php echo $this->Infinitas->date($user['User']['last_login']); ?></td>
					<td><?php echo $this->Infinitas->status($user['User']['active']); ?>&nbsp;</td>
				</tr> <?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>