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

	$massActions = $this->Infinitas->massActionButtons(
		array(
			'add',
			'edit',
			'copy',
			'delete'
		)
	);

	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);

	if(!$writable) { ?>
		<div class="error-message">
			<?php sprintf(__('Please ensure that %s is writable by the web server.'), $path); ?>
		</div><?php
	}
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox( 'all' ) => array(
						'class' => 'first',
						'style' => 'width:25px;'
					),
					$this->Paginator->sort('name'),
					$this->Paginator->sort('file_name'),
					__d('contents', 'Size'),
					__d('contents', 'Modified'),
				)
			);

			$id = 0;
			foreach ($pages as $page) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
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
					<td><?php echo CakeTime::niceShort($page['GlobalPage']['modified']); ?>&nbsp;</td>
				</tr><?php
				$id++;
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>