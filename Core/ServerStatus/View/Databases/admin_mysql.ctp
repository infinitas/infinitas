<?php
	/*
	 * Short Description / title.
	 *
	 * Overview of what the file does. About a paragraph or two
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 *
	 * @author {your_name}
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

echo $this->Html->tag('h1', __d('server_status', 'Local Variables') .
	$this->Html->tag('small', __d('server_status', 'Only variable that have been changed'))
);
?>
<table class="listing">
	<thead>
		<tr>
			<th style="width:200px;">Variable Name</th>
			<th>Default</th>
			<th>Current</th>
		</tr>
	</thead>
	<?php
		foreach ($localVars as $name => $value) {
			$globalVars[$name] = $this->Html->tag('span', $globalVars[$name], array('class' => 'label label-warning'));
			$value = $this->Html->tag('span', $value, array('class' => 'label label-info'));
			?>
			<tr>
				<td><?php echo $name; ?></td>
				<td><?php echo $globalVars[$name]; ?></td>
				<td><?php echo $value; ?></td>
			</tr> <?php
			$globalVars[$name] = sprintf('%s -> %s', $globalVars[$name], $value);
		}
	?>
</table>

<?php
	echo $this->Html->tag('h1', __d('server_status', 'Global Variables') .
		$this->Html->tag('small', __d('server_status', 'All MySQL configuration variables'))
	);
?>
<table class="listing">
	<thead>
		<tr>
			<th style="width:200px;">Variable Name</th>
			<th>Value</th>
		</tr>
	</thead>
	<?php
		foreach ($globalVars as $name => $value) { ?>
			<tr>
				<td><?php echo $name; ?></td>
				<td><?php echo $value; ?></td>
			</tr> <?php
		}
	?>
</table>