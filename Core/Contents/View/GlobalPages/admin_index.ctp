<?php
	/**
	 * Static Page Admin index
	 *
	 * Creating and maintainig static pages
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.views.admin_index
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dakota
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	echo $this->Form->create('GlobalPage', array('action' => 'mass'));
	$massActions = array(
		'add',
		'edit',
		'copy',
		'delete'
	);
	$errorMessage = null;
	if(!$writable) {
		$errorMessage = $this->Html->tag('div',
			$this->Html->tag('h4', __d('contents', 'Error')) .
			sprintf(__d('contents', 'Please ensure that %s is writable by the web server.'), str_replace(APP, 'APP/', $path)),
			array('class' => 'alert')
		);
		$massActions = false;
	}

	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
	echo $errorMessage;
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox( 'all' ) => array(
				'class' => 'first',
			),
			$this->Paginator->sort('name'),
			$this->Paginator->sort('file_name'),
			__d('contents', 'Size'),
			__d('contents', 'Modified'),
		));

		foreach ($pages as $page) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($page); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							Inflector::humanize($page['GlobalPage']['name']),
							array(
								'action' => 'edit',
								$page['GlobalPage']['file_name']
							)
						);
					?>&nbsp;
				</td>
				<td><?php echo $page['GlobalPage']['file_name']; ?>&nbsp;</td>
				<td><?php echo convert($page['GlobalPage']['size']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($page['GlobalPage']['modified']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');