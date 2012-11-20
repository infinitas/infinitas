<?php
	/**
	 * Comment Template.
	 *
	 * @todo -c Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.8a
	 */

	echo $this->Form->create('MailSystem', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'view',
		'reply',
		'forward',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('from'),
			$this->Paginator->sort('subject'),
			$this->EmailAttachments->hasAttachment(true) => array(
				'class' => 'actions',
				'width' => '20px'
			),
			$this->Paginator->sort('size'),
			$this->Paginator->sort('date'),
		));

		foreach($mails as $mail) {
			$class = $mail['Message']['unread'] ? 'unread' : ''; ?>
			<tr class="<?php echo $class; ?>">
				<td><?php echo $this->Infinitas->massActionCheckBox($mail); ?>&nbsp;</td>
				<td>
					<?php
						$_url = array('slugUrl' => array()); //$this->Event->trigger('Emails.slugUrl', array('type' => 'view', 'data' => $mail));
						echo $this->EmailAttachments->isFlagged($mail['MailSystem']),
							$this->Html->link(
								sprintf('%s (%s)', $mail['From']['name'], $mail['Email']['thread_count']),
								current($_url['slugUrl'])
							);
					?>&nbsp;
				</td>
				<td><?php echo $mail['Email']['subject']; ?>&nbsp;</td>
				<td><?php echo $this->EmailAttachments->hasAttachment($mail['MailSystem']); ?>&nbsp;</td>
				<td><?php echo convert($mail['Email']['size']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($mail['Email']['created']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');