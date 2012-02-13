<table cellspacing="0" cellpadding="0">
	<tr>
		<td width="50px">Id</td>
		<td width="100px">Plugin</td>
		<td width="100px">Controller</td>
		<td width="150px">Action</td>
		<td width="50px">Admin</td>
		<td width="50px">Registered</td>
		<td>Public</td>
	</tr>
	<?php
		foreach ($acos as $aco){
			?>
				<tr>
					<td><?php echo $aco['Aco']['id']; ?></td>
					<?php
						if (($aco['Aco']['lft']+2) < $aco['Aco']['rght']) {
							?>
								<td><?php echo $this->Html->link($aco['ParentAco']['alias'], array('controller' => 'acos', 'action' => 'view', $aco['ParentAco']['id'])); ?></td>
								<td>&nbsp;</td>
							<?php
						}

						else {
							?>
								<td>&nbsp;</td>
								<td><?php echo $this->Html->link($aco['ParentAco']['alias'], array('controller' => 'acos', 'action' => 'view', $aco['ParentAco']['id'])); ?></td>
							<?php
						}
					?>
					<td><?php echo $aco['Aco']['alias']; ?></td>
					<td>(Y)</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			<?php
		}
	?>
</table>