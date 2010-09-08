<?php
	/**
	 * Feed index
	 *
	 * Add some documentation for Feed.
	 *
	 * Copyright (c) {yourName}
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright     Copyright (c) 2009 {yourName}
	 * @link          http://infinitas-cms.org
	 * @package       Feed
	 * @subpackage    Feed.views.feeds.index
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create('Feed', array('url' => array('controller' => 'feeds', 'action' => 'mass', 'admin' => true)));

	$massActions = $this->Infinitas->massActionButtons(
		array(
			'add',
			'edit',
			'toggle',
			'copy',
			'delete',

			// other methods available
			// 'unlock',
		)
	);

	echo $this->Infinitas->adminIndexHead($this, $filterOptions, $massActions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox('all') => array(
						'class' => 'first',
						'style' => 'width:25px;'
					),
					$this->Paginator->sort('name'),
					$this->Paginator->sort('model') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('plugin') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('controller') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('action') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('limit') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('Group', 'Group.name') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('active') => array(
						'style' => 'width:50px;'
					),
					$this->Paginator->sort('modified') => array(
						'style' => 'width:100px;'
					),
				)
			);
			foreach ($feeds as $feed){ ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Form->checkbox($feed['Feed']['id']); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(
								$feed['Feed']['name'],
								array(
									'action' => 'edit',
									$feed['Feed']['id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo implode('.', array(ucfirst($feed['Feed']['plugin']), Inflector::singularize($feed['Feed']['controller']))); ?>&nbsp;</td>
					<td><?php echo $feed['Feed']['plugin']; ?>&nbsp;</td>
					<td><?php echo $feed['Feed']['controller']; ?>&nbsp;</td>
					<td><?php echo $feed['Feed']['action']; ?>&nbsp;</td>
					<td><?php echo $feed['Feed']['limit']; ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link($feed['Group']['name'], array('controller' => 'groups', 'action' => 'edit', $feed['Group']['id'])); ?>
					</td>
					<td><?php echo $this->Infinitas->status($feed['Feed']['active'], $feed['Feed']['id']); ?>&nbsp;</td>
					<td><?php echo $this->Time->niceShort($feed['Feed']['modified']); ?>&nbsp;</td>
				</tr><?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('admin/pagination/navigation'); ?>