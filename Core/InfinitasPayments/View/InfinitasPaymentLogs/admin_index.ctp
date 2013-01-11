<?php
/**
 * @brief Add some documentation for this admin_index form.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/InfinitasPayments
 * @package	   InfinitasPayments.View.admin_index
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

echo $this->Form->create(null, array('action' => 'mass'));
echo $this->Infinitas->adminIndexHead($filterOptions, array(
	'add',
	'edit',
	'toggle',
	'copy',
	'delete'
));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(
			array(
				$this->Form->checkbox('all') => array(
					'class' => 'first',
				),
				$this->Paginator->sort('infinitas_payment_id', __d('infinitas_payment', 'Method')),
				$this->Paginator->sort('transaction_id'),
				$this->Paginator->sort('transaction_type', __d('infinitas_payments', 'Type')),
				$this->Paginator->sort('total') => array(
					'class' => 'large'
				),
				$this->Paginator->sort('tax') => array(
					'class' => 'large'
				),
				$this->Paginator->sort('transaction_fee', __d('infinitas_payments', 'Fee')) => array(
					'class' => 'large'
				),
				$this->Paginator->sort('status') => array(
					'class' => 'small'
				),
				$this->Paginator->sort('modified', __d('infinitas_payments', 'Updated')) => array(
					'class' => 'date'
				),
				$this->Paginator->sort('transaction_date', __d('infinitas_payments', 'Transaction')) => array(
					'class' => 'date'
				)
			)
		);

		foreach ($infinitasPaymentLogs as $infinitasPaymentLog) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($infinitasPaymentLog); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link($infinitasPaymentLog['InfinitasPaymentMethod']['name'], array(
							'controller' => 'infinitas_payment_methods',
							'action' => 'edit',
							$infinitasPaymentLog['InfinitasPaymentMethod']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $infinitasPaymentLog['InfinitasPaymentLog']['transaction_id']; ?>&nbsp;</td>
				<td><?php echo $infinitasPaymentLog['InfinitasPaymentLog']['transaction_type']; ?>&nbsp;</td>
				<td>
					<?php
						echo CakeNumber::currency(
							$infinitasPaymentLog['InfinitasPaymentLog']['total'],
							$infinitasPaymentLog['InfinitasPaymentLog']['currency_code']
						);
					?>&nbsp;
				</td>
				<td>
					<?php
						echo CakeNumber::currency(
							$infinitasPaymentLog['InfinitasPaymentLog']['tax'],
							$infinitasPaymentLog['InfinitasPaymentLog']['currency_code']
						);
					?>&nbsp;
				</td>
				<td>
					<?php
						$percent = ($infinitasPaymentLog['InfinitasPaymentLog']['transaction_fee'] / $infinitasPaymentLog['InfinitasPaymentLog']['total']) * 100;
						$infinitasPaymentLog['InfinitasPaymentLog']['transaction_fee'] = CakeNumber::currency(
							$infinitasPaymentLog['InfinitasPaymentLog']['transaction_fee'],
							$infinitasPaymentLog['InfinitasPaymentLog']['currency_code']
						);
						echo $this->Design->label($infinitasPaymentLog['InfinitasPaymentLog']['transaction_fee'], array(
							'title' => __d('infinitas_payments', 'Percent of transaction %s', CakeNumber::toPercentage($percent))
						));
					?>&nbsp;
				</td>
				<td><?php echo $infinitasPaymentLog['InfinitasPaymentLog']['status']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($infinitasPaymentLog['InfinitasPaymentLog']); ?></td>
				<td><?php echo $this->Infinitas->date($infinitasPaymentLog['InfinitasPaymentLog']['transaction_date']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');