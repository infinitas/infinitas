<?php
	/**
	 * Feed Item index
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
	 * @package       FeedItem
	 * @subpackage    FeedItem.views.feed_items.index
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create('FeedItem', array('url' => array('controller' => 'feed_items', 'action' => 'mass', 'admin' => true)));

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

	echo $this->Infinitas->adminIndexHead($this, $paginator, $filterOptions, $massActions);
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
			foreach ($feedItems as $feedItem){ ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Form->checkbox($feedItem['FeedItem']['id']); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(
								$feedItem['FeedItem']['name'],
								array(
									'action' => 'edit',
									$feedItem['FeedItem']['id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo implode('.', array(ucfirst($feedItem['FeedItem']['plugin']), Inflector::singularize($feedItem['FeedItem']['controller']))); ?>&nbsp;</td>
					<td><?php echo $feedItem['FeedItem']['plugin']; ?>&nbsp;</td>
					<td><?php echo $feedItem['FeedItem']['controller']; ?>&nbsp;</td>
					<td><?php echo $feedItem['FeedItem']['action']; ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link($feedItem['Group']['name'], array('controller' => 'groups', 'action' => 'edit', $feedItem['Group']['id'])); ?>
					</td>
					<td><?php echo $this->Infinitas->status($feedItem['FeedItem']['active'], $feedItem['FeedItem']['id']); ?>&nbsp;</td>
					<td><?php echo $this->Time->niceShort($feedItem['FeedItem']['modified']); ?>&nbsp;</td>
				</tr><?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('admin/pagination/navigation'); ?>