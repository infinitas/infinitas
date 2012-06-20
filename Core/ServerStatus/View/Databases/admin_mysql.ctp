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
?>
<div class="table">
	<h1 class="no-gap">Local Variables<small>Only what is different</small></h1>
    <table class="listing no-gap" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th style="width:200px;">Variable Name</th>
				<th>Value</th>
			</tr>
		</thead>
		<?php
			foreach($localVars as $name => $value) {
				$globalVars[$name] = sprintf('<b style="color:red;">%s</b>', $globalVars[$name]);
				?>
					<tr>
						<td><?php echo $name; ?></td>
						<td><?php echo $value; ?></td>
					</tr>
				<?php
			}
		?>
	</table>
</div>

<div class="table">
	<h1 class="no-gap">Global Variables<small>Highlighted Items have been Changed localy</small></h1>
    <table class="listing no-gap" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th style="width:200px;">Variable Name</th>
				<th>Value</th>
			</tr>
		</thead>
		<?php
			foreach($globalVars as $name => $value) {
				?>
					<tr>
						<td><?php echo $name; ?></td>
						<td><?php echo $value; ?></td>
					</tr>
				<?php
			}
		?>
	</table>
</div>