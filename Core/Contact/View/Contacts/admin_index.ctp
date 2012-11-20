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
	 * @since		 0.5a
	 */

	echo $this->Form->create('Contact', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'move',
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
			$this->Paginator->sort('image') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('last_name', __d('contact', 'Name')),
			$this->Paginator->sort('email'),
			$this->Paginator->sort('Branch.name', __d('contact', 'Branch')) => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('position') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('phone') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('mobile') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('ordering') => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('active') => array(
				'style' => 'width:50px;'
			)
		));

		foreach ($contacts as $contact) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($contact); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->image('content/contact/contact/'.$contact['Contact']['image'], array(
							'height' => '35px;'
						));
					?>&nbsp;
				</td>
				<td>
					<?php
						echo $this->Html->link($contact['Contact']['last_name'].', '.$contact['Contact']['first_name'], array(
							'action' => 'edit', $contact['Contact']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $this->Text->autoLinkEmails($contact['Contact']['email']); ?>&nbsp;</td>
				<td><?php echo $contact['Branch']['name']; ?>&nbsp;</td>
				<td><?php echo $contact['Contact']['position']; ?>&nbsp;</td>
				<td><?php echo $contact['Contact']['phone']; ?>&nbsp;</td>
				<td><?php echo $contact['Contact']['mobile']; ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Infinitas->ordering($contact['Contact']['id'], $contact['Contact']['ordering'], 'Contact.Contact');
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->date($contact['Contact']['modified']); ?></td>
				<td><?php echo $this->Infinitas->status($contact['Contact']['active']); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');