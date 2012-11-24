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

    echo $this->Form->create('Newsletter', array('action' => 'mass'));
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
			$this->Paginator->sort('Campaign.name', __d('newsletter', 'Campaign')) => array(
				'style' => 'width:150px;'
			),
			$this->Paginator->sort('from') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('reply_to') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('sent') => array(
				'style' => 'width:100px;'
			),
			__d('newsletter', 'Status') => array(
				'class' => 'actions',
				'width' => '50px'
			)
		));

		foreach($newsletters as $newsletter) {
			?>
				<tr>
					<td><?php echo $this->Infinitas->massActionCheckBox($newsletter); ?>&nbsp;</td>
					<td><?php echo $newsletter['Newsletter']['subject']; ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(
								$newsletter['Campaign']['name'],
								array(
									'controller' => 'campaign',
									'action' => 'view',
									$newsletter['Newsletter']['campaign_id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo $newsletter['Newsletter']['from']; ?>&nbsp;</td>
					<td><?php echo $newsletter['Newsletter']['reply_to']; ?>&nbsp;</td>
					<td>
						<?php
							if ($newsletter['Newsletter']['active'] && !$newsletter['Newsletter']['sent']) {
								echo $this->Html->image(
									'core/icons/actions/16/update.png',
									array(
										'alt' => __d('newsletter', 'In Progress'),
										'title' => __d('newsletter', 'Busy sending')
									)
								);
							} else {
								echo $this->Infinitas->status($newsletter['Newsletter']['sent']);
							}
						?>&nbsp;
					</td>
					<td>
						<?php
							if ($newsletter['Newsletter']['sent']) {
								echo $this->Html->link(
									$this->Html->image(
										'core/icons/actions/16/reports.png'
									),
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
							} else {
								echo $this->Infinitas->status($newsletter['Newsletter']['active']);
							}
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