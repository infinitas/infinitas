<table class="listing" cellpadding="0" cellspacing="0">
	<?php
		echo $this->Core->adminTableHeader(
			array(
				__('Username', true),
				__('Browser', true) => array(
					'style' => 'width:75px;'
				),
				__('OS', true) => array(
					'style' => 'width:75px;'
				),
				__('Country', true) => array(
					'style' => 'width:75px;'
				),
				__('Last Login', true) => array(
					'style' => 'width:75px;'
				)
			)
		);

		foreach ( $users as $user )
		{
			?>
				<tr class="<?php echo $this->Core->rowClass(); ?>">
					<td>
						<?php echo $this->Html->link( $user['User']['username'], array('action' => 'edit', $user['User']['id'])); ?>&nbsp;
					</td>
					<td>
						<?php echo $user['User']['browser']; ?>&nbsp;
					</td>
					<td>
						<?php echo $user['User']['operating_system']; ?>&nbsp;
					</td>
					<td>
						<?php echo $user['User']['country']; ?>&nbsp;
					</td>
					<td>
						<?php echo $this->Time->niceShort($user['User']['last_login']); ?>&nbsp;
					</td>
				</tr>
			<?php
		}
	?>
</table>