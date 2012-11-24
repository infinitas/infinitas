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

	echo $this->Form->create(null, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('plugin') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('name') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('street'),
			$this->Paginator->sort('city'),
			$this->Paginator->sort('province'),
			$this->Paginator->sort('postal'),
			$this->Paginator->sort(__d('contact', 'Country'), 'Country.name') => array(
				'style' => 'width:75px;'
			),
			__d('contact', 'Continent') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:50px;'
			)
		));

		foreach ($addresses as $address) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($address); ?>&nbsp;</td>
				<td><?php echo implode('.', array($address['ContactAddress']['plugin'], $address['ContactAddress']['model'])); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link($address['ContactAddress']['name'], array(
							'action' => 'edit',
							$address['ContactAddress']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $address['ContactAddress']['street']; ?>&nbsp;</td>
				<td><?php echo $address['ContactAddress']['city']; ?>&nbsp;</td>
				<td><?php echo $address['ContactAddress']['province']; ?>&nbsp;</td>
				<td><?php echo $address['ContactAddress']['postal']; ?>&nbsp;</td>
				<td><?php echo $address['Country']['name']; ?>&nbsp;</td>
				<td><?php echo __d('contact', Configure::read('Contact.continents.' . $address['ContactAddress']['continent_id'])); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($address['ContactAddress']['modified']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');