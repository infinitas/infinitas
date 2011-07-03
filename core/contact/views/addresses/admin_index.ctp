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

	echo $this->Form->create('Address', array('action' => 'mass'));
		$massActions = $this->Infinitas->massActionButtons(
			array(
				'add',
				'edit',
				'copy',
				'delete'
			)
		);
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox('all') => array(
						'class' => 'first',
						'style' => 'width:25px;'
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
					$this->Paginator->sort(__('Country', true), 'Country.name') => array(
						'style' => 'width:75px;'
					),
					__('Continent', true) => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('modified') => array(
						'style' => 'width:50px;'
					)
				)
			);

			$i = 0;
			foreach ($addresses as $address){
				?>
					<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
						<td><?php echo $this->Form->checkbox($address['Address']['id']); ?>&nbsp;</td>
						<td>
							<?php echo sprintf('%s.%s', $address['Address']['plugin'], $address['Address']['model']); ?>&nbsp;
						</td>
						<td>
							<?php echo $this->Html->link($address['Address']['name'], array('action' => 'edit', $address['Address']['id'])); ?>&nbsp;
						</td>
						<td>
							<?php echo $address['Address']['street']; ?>&nbsp;
						</td>
						<td>
							<?php echo $address['Address']['city']; ?>&nbsp;
						</td>
						<td>
							<?php echo $address['Address']['province']; ?>&nbsp;
						</td>
						<td>
							<?php echo $address['Address']['postal']; ?>&nbsp;
						</td>
						<td>
							<?php echo $address['Country']['name']; ?>&nbsp;
						</td>
						<td>
							<?php echo __(Configure::read('Contact.continents.' . $address['Address']['continent_id']), true); ?>&nbsp;
						</td>
						<td>
							<?php echo $this->Time->niceShort($address['Address']['modified']); ?>&nbsp;
						</td>
					</tr>
				<?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>