<?php
	class OrderAppController extends AppController {
		var $helpers = array(
			'Shop.Shop',
			'Filter.Filter'
		);

		var $components = array(
			'Shop.Shop'
		);

		function beforeRender(){
			parent::beforeRender();
		}

		function beforeFilter(){
			parent::beforeFilter();
			$data = $this->Event->trigger('loadPaymentGateways');

			$gateways = array();
			foreach($data['loadPaymentGateways'] as $gateway){
				$gateways[] = $gateway;
			}
			Configure::write('Shop.payment_methods', $gateways);
			Configure::write('Order.notify_url', 'http://'.env('SERVER_NAME').$this->webroot.'order/orders/recive_payment');
		}

		function admin_mass() {
			$massAction = $this->MassAction->getAction($this->params['form']);
			if(strtolower($massAction) == 'export'){
				$this->redirect($this->referer().'.csv');
			}

			return parent::admin_mass();
		}
	}