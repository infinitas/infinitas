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
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(array(
				$this->Form->checkbox('all') => array(
					'class' => 'first'
				),
				$this->Paginator->sort('name') => array(
					'class' => 'larger'
				),
				$this->Paginator->sort('author') => array(
					'class' => 'larger'
				),
				$this->Paginator->sort('dependancies'),
				$this->Paginator->sort('version') => array(
					'class' => 'small'
				),
				$this->Paginator->sort('license') => array(
					'class' => 'small'
				),
				$this->Paginator->sort('active') => array(
					'class' => 'status'
				),
				$this->Paginator->sort('core') => array(
					'class' => 'status'
				),
				$this->Paginator->sort('created', __d('installer', 'Installed')) => array(
					'class' => 'date'
				),
				$this->Paginator->sort('modified', __d('installer', 'Updated')) => array(
					'class' => 'date'
				)
			));

            foreach ($plugins as $plugin) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Infinitas->massActionCheckBox($plugin); ?>&nbsp;</td>
					<td><?php echo $plugin['Plugin']['name']; ?>&nbsp;</td>
					<td><?php echo $this->Html->link($plugin['Plugin']['author'], $plugin['Plugin']['website']); ?>&nbsp;</td>
					<td>
						<?php
							$plugin['Plugin']['dependancies'] = json_decode($plugin['Plugin']['dependancies'], true);
							if(empty($plugin['Plugin']['dependancies'])) {
								$plugin['Plugin']['dependancies'] = array('-');
							}
							echo $this->Text->toList($plugin['Plugin']['dependancies']);
						?>&nbsp;
					</td>
					<td><?php echo sprintf('v%s', $plugin['Plugin']['version']); ?>&nbsp;</td>
					<td><?php echo $plugin['Plugin']['license']; ?>&nbsp;</td>
					<td><?php echo $this->Infinitas->status($plugin['Plugin']['active']); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Infinitas->status($plugin['Plugin']['core'], array(
								'title_yes' => __d('installer', 'Core plugin :: This plugin is part of the Infinitas core'),
								'title_no' => __d('installer', 'Plugin :: This plugin is not part of the Infinitas core'),
							));
						?>&nbsp;
					</td>
					<td><?php echo $this->Infinitas->date($plugin['Plugin']['created']); ?>&nbsp;</td>
					<td>
						<?php
							$out = __d('installer', 'Never');
							if($plugin['Plugin']['created'] != $plugin['Plugin']['modified']) {
								$out = $this->Infinitas->date($plugin['Plugin']['modified']);
							}
							echo $out;
						?>&nbsp;
					</td>
				</tr> <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>