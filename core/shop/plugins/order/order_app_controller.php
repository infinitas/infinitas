<?php
	class OrderAppController extends AppController {
		var $helpers = array(
			'Shop.Shop',
			'Filter.Filter',
			'Data.Csv'
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
			switch(strtolower($massAction)){
				case 'export':
					$this->redirect($this->referer().'.csv');
					break;

				case 'save':
					$this->save();
					break;

				default:
					return parent::admin_mass();
					break;
			}
		}

		function save(){
			$data[$this->modelClass] = $this->data['Save'];

			if($this->{$this->modelClass}->saveAll($data[$this->modelClass])){
				$this->Session->setFlash(__('All items updated', true));
				$this->redirect($this->referer());
			}

			$this->Session->setFlash(__('There was a problem updating the data', true));
			$this->redirect($this->referer());
		}
	}