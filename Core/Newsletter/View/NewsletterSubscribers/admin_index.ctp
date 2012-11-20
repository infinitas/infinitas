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
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    echo $this->Form->create('NewsletterSubscriber', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'toggle',
		'delete'
	));

?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('subscriber_name'),
			$this->Paginator->sort('subscriber_email'),
			$this->Paginator->sort('subscription_count', __d('newsletter', 'Subscriptions')) => array(
				'width' => '50px'
			),
			$this->Paginator->sort('created') => array(
				'width' => '120px'
			),
			$this->Paginator->sort('modified') => array(
				'width' => '120px'
			),
			__d('newsletter', 'Status') => array(
				'class' => 'actions',
				'width' => '50px'
			)
		));

		foreach($newsletterSubscribers as $newsletterSubscriber) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($newsletterSubscriber); ?>&nbsp;</td>
				<td>
					<?php
						echo $newsletterSubscriber['NewsletterSubscriber']['subscriber_name'];
						if(!empty($newsletterSubscriber['User']['id'])) {
							echo $this->Image->image(
								'actions',
								'new-window',
								array(
									'title' => __d('newsletter', 'View'),
									'alt' => __d('newsletter', 'View'),
									'width' => '12px',
									'url' => $this->Infinitas->adminQuickLink($newsletterSubscriber['User'], array('plugin' => 'users', 'controller' => 'users'), 'User', true)
								)
							);
						}
					?>&nbsp;
				</td>
				<td>
					<?php
						if(!empty($newsletterSubscriber['User']['id'])) {
							echo $newsletterSubscriber['User']['email'];
						} else {
							echo $newsletterSubscriber['NewsletterSubscriber']['email'];
						}
					?>&nbsp;
				</td>
				<td><?php echo $newsletterSubscriber['NewsletterSubscriber']['subscription_count']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($newsletterSubscriber['NewsletterSubscriber']['created']); ?></td>
				<td><?php echo $this->Infinitas->date($newsletterSubscriber['NewsletterSubscriber']['modified']); ?></td>
				<td>
					<?php
						echo $this->Infinitas->status($newsletterSubscriber['NewsletterSubscriber']['active'], $newsletterSubscriber['NewsletterSubscriber']['id']);
					?>&nbsp;
				</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');