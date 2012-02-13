<?php
	/**
	 *
	 *
	 */
	class IpAddressesController extends ManagementAppController {
		public function admin_index() {
			$this->IpAddress->recursive = 1;
			$ipAddresses = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'ip_address',
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('ipAddresses', 'filterOptions'));
		}
	}