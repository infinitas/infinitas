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

	echo $this->Form->create(false, array('action' => 'mass'));
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
			$this->Paginator->sort('image') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('name'),
			$this->Paginator->sort('phone') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('fax') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('user_count', __d('contact', 'Contacts')) => array(
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

		foreach ($branches as $branch) {?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($branch); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->image(
							'content/contact/branch/'.$branch['Branch']['image'],
							array(
								'height' => '35px;'
							)
						);
					?>&nbsp;
				</td>
				<td>
					<?php
						echo $this->Html->link($branch['Branch']['name'], array(
							'action' => 'edit',
							$branch['Branch']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $branch['Branch']['phone']; ?>&nbsp;</td>
				<td><?php echo $branch['Branch']['fax']; ?>&nbsp;</td>
				<td><?php echo $this->Design->count($branch['Branch']['user_count']); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Infinitas->ordering($branch['Branch']['id'], $branch['Branch']['ordering'], 'Contact.Branch');
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->date($branch['Branch']['modified']); ?></td>
				<td><?php echo $this->Infinitas->status($branch['Branch']['active']); ?>&nbsp;</td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');