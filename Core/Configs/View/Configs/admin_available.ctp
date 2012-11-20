<?php
    echo $this->Form->create('Config', array('action' => 'mass'));
		echo $this->Infinitas->adminIndexHead(null, array(
			'add'
		));
	echo $this->Form->end();

	echo $this->Html->tag('h1', __d('configs', 'Overloaded Values') .
		$this->Html->tag('small', __d('configs', 'Click a link below to edit / delete the overladed value'))
	);
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			__d('configs', 'Key') => array(
				'style' => 'width:200px;'
			),
			__d('configs', 'Value') => array(
				'style' => 'width:200px;'
			)
		));

		foreach ($overloaded as $key => $value) {
			unset($configs[$key]); ?>
			<tr>
				<td>
					<?php
						echo $this->Html->link($key, array(
							'action' => 'index',
							'Config.key' => $key
						));
					?>&nbsp;
				</td>
				<td><?php echo $value; ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>

<?php
	echo $this->Html->tag('h1', __d('configs', 'Default Values') .
		$this->Html->tag('small', __d('configs', 'Click a link below to overload the config with new values'))
	);
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			__d('configs', 'Key') => array(
				'style' => 'width:200px;'
			),
			__d('configs', 'Value') => array(
				'style' => 'width:200px;'
			)
		));

		foreach ($configs as $key => $value) {?>
			<tr>
				<td>
					<?php
						echo $this->Html->link($key, array(
							'action' => 'add',
							'Config.key' => $key
						));
					?>&nbsp;
				</td>
				<td><?php echo $value; ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>