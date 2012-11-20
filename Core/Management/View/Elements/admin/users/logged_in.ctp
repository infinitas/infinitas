<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(
			array(
				__('Username'),
				__('Browser') => array(
					'style' => 'width:75px;'
				),
				__('OS') => array(
					'style' => 'width:75px;'
				),
				__('Country') => array(
					'style' => 'width:75px;'
				),
				__('Last Login') => array(
					'style' => 'width:75px;'
				)
			)
		);

		foreach ( $users as $user )
		{
			?>
				<tr>
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