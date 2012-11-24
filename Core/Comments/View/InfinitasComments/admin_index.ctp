<?php
    /**
     * Management Comments admin index view file.
     *
     * this is the admin index file that displays a list of comments in the
     * admin section of the blog plugin.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.comments.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

	echo $this->Form->create('InfinitasComment', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'edit',
		'toggle',
		'spam',
		'ban',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort(__d('comments', 'Where'), 'class'),
			$this->Paginator->sort('email') => array(
				'style' => '50px;'
			),
			$this->Paginator->sort('ip_address'),
			$this->Paginator->sort('created') => array(
				'class' => 'date'
			),
			$this->Paginator->sort('points') => array(
				'width' => '50px'
			),
			__d('comments', 'Status') => array(
				'class' => 'actions'
			)
		));

		foreach($comments as $comment) { ?>
			<tr class="multi-line">
				<td><?php echo $this->Infinitas->massActionCheckBox($comment); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							$comment['InfinitasComment']['class'],
							array(
								'Comment.class' => $comment['InfinitasComment']['class']
							)
						);
					?>&nbsp;
				</td>
				<td><?php echo $this->Text->autoLinkEmails($comment['InfinitasComment']['email']); ?>&nbsp;</td>
				<td><?php echo $comment['InfinitasComment']['ip_address']; ?>&nbsp;</td>
				<td><?php echo $this->Time->timeAgoInWords($comment['InfinitasComment']['created']); ?>&nbsp;</td>
				<td><?php echo $comment['InfinitasComment']['points']; ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Infinitas->status($comment['InfinitasComment']['active'], $comment['InfinitasComment']['id']);
						if(!$comment['InfinitasComment']['active']) {
							echo ' ', Inflector::humanize($comment['InfinitasComment']['status']);
						}
						echo $this->Infinitas->status(
							$comment['InfinitasComment']['mx_record'],
							array(
								'title_yes' => __d('comments', 'MX Record :: The email address seems valid'),
								'title_no' => __d('comments', 'MX Record :: This email address could be fake'),
							)
						);
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
						echo $this->Html->link($comment['InfinitasComment']['post'], array(
							'action' => 'edit', $comment['InfinitasComment']['id']
						));
					?>&nbsp;
				</td>
				<td colspan="100">
					<?php echo str_replace('\n', ' ', strip_tags($comment['InfinitasComment']['comment'])); ?>
				</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');