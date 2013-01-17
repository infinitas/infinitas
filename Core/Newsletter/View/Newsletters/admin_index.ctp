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

    echo $this->Form->create(null, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'view',
		'edit',
		'copy',
		'toggle',
		'send',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('subject'),
			$this->Paginator->sort('NewsletterCampaign.name', __d('newsletter', 'Campaign')) => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('from') => array(
				'class' => 'large'
			),
			$this->Paginator->sort('reply_to') => array(
				'class' => 'large'
			),
			__d('newsletter', 'Status') => array(
				'class' => 'medium'
			)
		));

		foreach ($newsletters as $newsletter) {
			?>
				<tr>
					<td><?php echo $this->Infinitas->massActionCheckBox($newsletter); ?>&nbsp;</td>
					<td><?php echo $newsletter['Newsletter']['subject']; ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(
								$newsletter['NewsletterCampaign']['name'],
								array(
									'controller' => 'newsletter_campaigns',
									'action' => 'edit',
									$newsletter['Newsletter']['newsletter_campaign_id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo $this->Text->autoLinkEmails($newsletter['Newsletter']['from']); ?>&nbsp;</td>
					<td><?php echo $this->Text->autoLinkEmails($newsletter['Newsletter']['reply_to']); ?>&nbsp;</td>
					<td>
						<?php
							if ($newsletter['Newsletter']['active'] && !$newsletter['Newsletter']['sent']) {
								echo $this->Infinitas->status($newsletter['Newsletter']['sent'], array(
									'title_no' => __d('newsletter', 'Sending in progress')
								));
							}
							if ($newsletter['Newsletter']['sent']) {
								echo $this->Html->link(
									$this->Design->icon('bar-chart'),
									array(
										'action' => 'report',
										$newsletter['Newsletter']['id']
									),
									array(
										'title' => __d('newsletter', 'Sending complete. See the report.'),
										'alt' => __d('newsletter', 'Done', true ),
										'escape' => false
									)
								);
							}
							echo $this->Infinitas->status($newsletter['Newsletter']['active'], array(
								'title_no' => __d('newsletter', 'This newsletter is not sending')
							));
						?>&nbsp;
					</td>
				</tr>
			<?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');