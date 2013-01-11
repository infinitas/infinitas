<?php
	/**
	 * @brief Add some documentation for this admin_edit form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link    http://infinitas-cms.org/InfinitasPayments
	 * @package	InfinitasPayments.views.admin_edit
	 * @license	http://infinitas-cms.org/mit-license The MIT License
	 * @since   0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	echo $this->Form->create();
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Infinitas payment logs'); ?></h1><?php
				echo $this->Form->input('id');
				echo $this->Form->input('token');
				echo $this->Form->input('transaction_id', array('empty' => Configure::read('Website.empty_select')));
				echo $this->Form->input('transaction_type');
				echo $this->Form->input('transaction_fee');
				echo $this->Form->input('transaction_date');
				echo $this->Form->input('currency_code');
				echo $this->Form->input('total');
				echo $this->Form->input('tax');
				echo $this->Form->input('custom');
				echo $this->Form->input('status');
				echo $this->Infinitas->wysiwyg('InfinitasPaymentLog.raw_request');
				echo $this->Infinitas->wysiwyg('InfinitasPaymentLog.raw_response');
			?>
		</fieldset>

		<fieldset>
			<h1><?php echo __('Configuration'); ?></h1><?php
		?>
		</fieldset><?php
	echo $this->Form->end();
