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

echo $this->Form->create('Campaign', array('action' => 'mass'));
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
			$this->Paginator->sort('description'),
			$this->Paginator->sort('Template.name', __d('newsletter', 'Template')),
			$this->Paginator->sort('newsletter_count', __d('newsletter', 'Newsletters')) => array(
				'width' => '50px'
			),
			$this->Paginator->sort('created') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:100px;'
			),
			__d('newsletter', 'Status') => array(
				'class' => 'actions',
				'width' => '50px'
			)
		));

		foreach ($campaigns as $campaign) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($campaign); ?>&nbsp;</td>
				<td><?php echo $campaign['Campaign']['name']; ?>&nbsp;</td>
				<td><?php echo $campaign['Campaign']['description']; ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							$campaign['Template']['name'],
							array(
								'controller' => 'templates',
								'action' => 'view',
								$campaign['Template']['id']
							)
						);
					?>&nbsp;
				</td>
				<td style="text-align:center;"><?php echo $campaign['Campaign']['newsletter_count']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($campaign['Campaign']['created']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($campaign['Campaign']['modified']); ?>&nbsp;</td>
				<td>
					<?php
						$newsletterStatuses = Set::extract('/Newsletter/sent', $campaign);
						$campaignSentStatus = true;

						if (empty($newsletterStatuses)) {
							echo $this->Html->link(
								$this->Html->image(
									'core/icons/actions/16/warning.png',
									array(
										'alt' => __d('newsletter', 'No Mails'),
										'title' => __d('newsletter', 'This Campaign has no mails. Click to add some')
									)
								),
								array(
									'controller' => 'newsletters',
									'action' => 'add',
									'campaign_id' => $campaign['Campaign']['id']
								),
								array(
									'escape' => false
								)
							);
						} else {
							foreach ($newsletterStatuses as $newsletterStatus) {
								$campaignSentStatus = $campaignSentStatus && $newsletterStatus;
							}

							if ($campaignSentStatus) {
								echo __d('newsletter', 'All Sent');
							} else {
								echo $this->Infinitas->status($campaign['Campaign']['active'], $campaign['Campaign']['id']);
							}
						}

						echo $this->Locked->display($campaign);
					?>&nbsp;
				</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation'); ?>