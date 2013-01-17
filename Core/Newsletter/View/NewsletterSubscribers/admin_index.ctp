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
			$this->Paginator->sort('subscriber_name', __d('newsletter', 'Name')) => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('subscriber_email', __d('newsletter', 'Email')),
			$this->Paginator->sort('subscription_count', __d('newsletter', 'Subscriptions')) => array(
				'class' => 'small'
			),
			$this->Paginator->sort('modified') => array(
				'class' => 'date'
			),
			__d('newsletter', 'Status') => array(
				'class' => 'medium'
			)
		));

		foreach ($newsletterSubscribers as $newsletterSubscriber) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($newsletterSubscriber); ?>&nbsp;</td>
				<td>
					<?php
						echo $newsletterSubscriber['NewsletterSubscriber']['subscriber_name'];
						if (!empty($newsletterSubscriber['User']['id'])) {
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
						if (!empty($newsletterSubscriber['User']['id'])) {
							echo $newsletterSubscriber['User']['email'];
						} else {
							echo $newsletterSubscriber['NewsletterSubscriber']['email'];
						}
					?>&nbsp;
				</td>
				<td><?php echo $this->Design->count($newsletterSubscriber['NewsletterSubscriber']['newsletter_subscription_count']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($newsletterSubscriber['NewsletterSubscriber']); ?></td>
				<td>
					<?php
						echo $this->Infinitas->status($newsletterSubscriber['NewsletterSubscriber']['active'], array(
							'title_no' => __d('newsletter', 'The users subscription is not active')
						));
					?>&nbsp;
				</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');