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
echo $this->Filter->alphabetFilter();
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(
			array(
				$this->Form->checkbox('all') => array(
					'class' => 'first',
				),
				$this->Paginator->sort('name'),
				$this->Paginator->sort('slug', __d('infinitas_payments', 'Alias')) => array(
					'class' => 'large'
				),
				$this->Paginator->sort('infinitas_payment_log_count', __d('infinitas_payments', 'Payments')) => array(
					'class' => 'small'
				),
				$this->Paginator->sort('modified') => array(
					'class' => 'date'
				),
				__d('infinitas_payments', 'Status') => array(
					'class' => 'large'
				)
			)
		);

		foreach ($infinitasPaymentMethods as $infinitasPaymentMethod) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($infinitasPaymentMethod); ?>&nbsp;</td>
				<td>
					<?php 
						echo implode($this->Html->tag('br'), array(
							$this->Html->image($infinitasPaymentMethod['InfinitasPaymentMethod']['image_thumb'], array(
								'width' => 75
							)),
							$this->Html->adminQuickLink($infinitasPaymentMethod['InfinitasPaymentMethod'])
						));
					?>
				</td>
				<td><?php echo $this->Design->label($infinitasPaymentMethod['InfinitasPaymentMethod']['slug']); ?></td>
				<td><?php echo $this->Design->count($infinitasPaymentMethod['InfinitasPaymentMethod']['infinitas_payment_log_count']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($infinitasPaymentMethod['InfinitasPaymentMethod']); ?></td>
				<td>
					<?php
						echo $this->Infinitas->status($infinitasPaymentMethod['InfinitasPaymentMethod']['active'], array(
							'title_no' => __d('infinitas_payments', 'The payment method is disabled')
						));
						if ($infinitasPaymentMethod['InfinitasPaymentMethod']['testing']) {
							echo $this->Html->link($this->Design->icon('bolt'), $this->here . '#', array(
								'title' => __d('infinitas_payments', 'Sandbox mode is active'),
								'escape' => false
							));
						}
					?>
				</td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');