<?php
/**
 * manage the plugins on the site
 *
 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link          http://infinitas-cms.org
 * @package       sort
 * @subpackage    sort.comments
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since         0.5a
 */

echo $this->Form->create('Plugin', array('action' => 'mass'));
echo $this->Infinitas->adminIndexHead($filterOptions, array(
	'install',
	'uninstall',
	'toggle',
	'delete'
)); ?>

<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('name') => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('dependancies'),
			$this->Paginator->sort('author') => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('version') => array(
				'class' => 'small'
			),
			$this->Paginator->sort('created', __d('installer', 'Installed')) => array(
				'class' => 'date'
			),
			$this->Paginator->sort('modified', __d('installer', 'Updated')) => array(
				'class' => 'date'
			),
			$this->Paginator->sort('active', __d('installer', 'Status')) => array(
				'class' => 'status'
			),
		));

		foreach ($plugins as $plugin) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($plugin); ?>&nbsp;</td>
				<td><?php echo $plugin['Plugin']['name']; ?>&nbsp;</td>
				<td>
					<?php
						$plugin['Plugin']['dependancies'] = json_decode($plugin['Plugin']['dependancies'], true);
						if (empty($plugin['Plugin']['dependancies'])) {
							$plugin['Plugin']['dependancies'] = array('-');
						}
						echo $this->Text->toList($plugin['Plugin']['dependancies']);
					?>&nbsp;
				</td>
				<td>
					<?php
						echo $this->Design->license($plugin['Plugin']['license']);
						echo $this->Html->link($plugin['Plugin']['author'], $plugin['Plugin']['website']);
					?>&nbsp;
				</td>
				<td><?php echo sprintf('v%s', $plugin['Plugin']['version']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($plugin['Plugin']['created']); ?></td>
				<td>
					<?php
						$out = __d('installer', 'Never') . '&nbsp;';
						if ($plugin['Plugin']['created'] != $plugin['Plugin']['modified']) {
							$out = $this->Infinitas->date($plugin['Plugin']['modified']);
						}
						echo $out;
					?>
				</td>
				<td>
					<?php
						if($plugin['Plugin']['core']) {
							echo $this->Html->link($this->Design->icon('locked'), $this->here, array(
								'title' => __d('installer', 'This is part of the infinitas core'),
								'class' => 'locks',
								'escape' => false
							));
						}
						echo $this->Infinitas->status($plugin['Plugin']['active']);
					?>&nbsp;
				</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');