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
	 * @copyright	 Copyright (c) 2009 {yourName}
	 * @link		  http://infinitas-cms.org
	 * @package	   Feed
	 * @subpackage	Feed.views.feeds.index
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create('Feed', array('url' => array('controller' => 'feeds', 'action' => 'mass', 'admin' => true)));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'toggle',
		'copy',
		'delete',
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('name'),
			$this->Paginator->sort('model') => array(
				'style' => 'width:75px;'
			),
			__d('feed', 'Links To'),
			$this->Paginator->sort('limit') => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('Group.name', __d('feed', 'For')) => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('active') => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:100px;'
			),
		));

		foreach ($feeds as $feed) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($feed); ?>&nbsp;</td>
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
				<td>
					<?php
						echo Router::url(
							array(
								'plugin' => $feed['Feed']['plugin'],
								'controller' => $feed['Feed']['controller'],
								'action' => $feed['Feed']['action'],
								'slug' => 'a-page',
								'admin' => $feed['Group']['name'] == 'admin' ? true : false
							)
						);
					?>&nbsp;
				</td>
				<td><?php echo $feed['Feed']['limit']; ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link($feed['Group']['name'], array(
							'controller' => 'groups',
							'action' => 'edit',
							$feed['Group']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->status($feed['Feed']['active'], $feed['Feed']['id']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($feed['Feed']['modified']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');