<?php
	/**
	 * Core delete confirmation.
	 *
	 * show a confirm page for delete when there is no javascript
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package core
	 * @subpackage core.views.global.delete
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	echo $this->Form->create($model);
	echo $this->Infinitas->adminIndexHead(null, array(
		'delete',
		'cancel'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(
			array(
				$this->Form->checkbox('all') => array(
					'class' => 'first'
				),
				__('Record') => array(
					'style' => 'width:150px;'
				)
			)
		);

		foreach($rows as $id => $value) { ?>
			<tr>
				<td>
					<?php
						echo $this->Infinitas->massActionCheckBox(
							array($model => array('id' => $id)),
							array('checked' => true, 'model' => $model, 'primaryKey' => 'id')
						);
					?>&nbsp;
				</td>
				<td><?php echo $value; ?>&nbsp;</td>
			</tr> <?php
		}

		echo $this->Form->hidden('Confirm.model', array('value' => $model));
		echo $this->Form->hidden('Confirm.confirmed', array('value' => 1));
	?>
</table>
<?php
	echo $this->Form->end();