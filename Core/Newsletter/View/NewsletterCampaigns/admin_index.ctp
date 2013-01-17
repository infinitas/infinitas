<?php
/**
 * Newsletter campaign list
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Infinitas.Newsletter.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Form->create(null, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
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
			$this->Paginator->sort('name'),
			$this->Paginator->sort('Template.name', __d('newsletter', 'Template')) => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('newsletter_count', __d('newsletter', 'Newsletters')) => array(
				'class' => 'small'
			),
			$this->Paginator->sort('modified') => array(
				'class' => 'date'
			),
			__d('newsletter', 'Status') => array(
				'class' => 'small'
			)
		));

		foreach ($newsletterCampaigns as $newsletterCampaign) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($newsletterCampaign); ?>&nbsp;</td>
				<td><?php echo $newsletterCampaign['NewsletterCampaign']['name']; ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link($newsletterCampaign['NewsletterTemplate']['name'], array(
							'controller' => 'newsletter_templates',
							'action' => 'view',
							$newsletterCampaign['NewsletterTemplate']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $this->Design->count($newsletterCampaign['NewsletterCampaign']['newsletter_count']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($newsletterCampaign['NewsletterCampaign']); ?></td>
				<td>
					<?php
						$newsletterStatuses = Set::extract('/Newsletter/sent', $newsletterCampaign);
						$campaignSentStatus = true;

						if (empty($newsletterStatuses)) {
							echo $this->Html->link(
								$this->Design->icon('add'),
								array(
									'controller' => 'newsletters',
									'action' => 'add',
									'campaign_id' => $newsletterCampaign['NewsletterCampaign']['id']
								),
								array(
									'escape' => false,
									'title' => __d('newsletter', 'This Campaign has no mails. Click to add some')
								)
							);
						} else {
							foreach ($newsletterStatuses as $newsletterStatus) {
								$campaignSentStatus = $campaignSentStatus && $newsletterStatus;
							}

							if ($campaignSentStatus) {
								echo __d('newsletter', 'All Sent');
							} else {
								echo $this->Infinitas->status($newsletterCampaign['NewsletterCampaign']['active'], $newsletterCampaign['NewsletterCampaign']['id']);
							}
						}

						// echo $this->Locked->display($newsletterCampaign);
					?>
				</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation'); ?>