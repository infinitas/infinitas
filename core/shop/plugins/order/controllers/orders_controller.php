<?php
	class OrdersController extends OrderAppController{
		var $name = 'Orders';

		function index(){
			$user_id = $this->Session->read('Auth.User.id');

			$this->paginate = array(
				'conditions' => array(
					'Order.user_id' => $user_id
				),
				'contain' => array(
					'User',
					'Address',
					'Status'
				),
				'order' => array(
					'Status.ordering' => 'ASC'
				)
			);

			$orders = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'user_id' => $this->Order->User->find('list'),
				'status_id' => $this->Order->Status->find('list'),
				'payment_method' => array(),
				'shipping_method' => array()
			);
			$this->set(compact('orders','filterOptions'));
		}

		function checkout(){
			$user_id = $this->Session->read('Auth.User.id');
			if($user_id < 1){
				$this->Session->setFlash(__('You need to be logged in to checkout', true));
				$this->redirect(array('plugin' => 'shop', 'controller' => 'carts', 'action' => 'index'));
			}

			$cartItems = ClassRegistry::init('Shop.Cart')->getCartData($user_id);
			if(empty($cartItems)){
				$this->Session->setFlash(__('You dont have any products', true));
				$this->redirect(array('plugin' => 'shop', 'controller' => 'products', 'action' => 'dashboard'));
			}

			$this->data['Order']['status_id'] = $this->Order->Status->getFirst();
			$this->data['Order']['user_id'] = $user_id;
			$this->data['Order']['tracking_number'] = '';

			foreach($cartItems as $item){
				unset($item['Cart']['created']);
				unset($item['Cart']['modified']);
				unset($item['Cart']['sub_total']);
				unset($item['Cart']['deleted']);
				unset($item['Cart']['deleted_date']);
				$this->data['Item'][] = $item['Cart'];
			}

			if($this->Order->saveAll($this->data)){
				ClassRegistry::init('Shop.Cart')->clearCart($user_id);
				$this->Session->setFlash(__('Your order has been completed and now requires payment', true));
				$this->redirect(array('action' => 'pay'));
			}

			$this->Session->setFlash(__('Your order has been completed and now requires payment', true));
			$this->redirect(array('action' => 'pay'));
		}

		function pay(){
			$orders = $this->Order->getPendingOrders($this->Session->read('Auth.User.id'));
			if(empty($orders)){
				$this->Session->setFlash(__('It seems you do not have any orders that require payment', true));
				$this->redirect(array('action' => 'index'));
			}

			$paymentMethods = Configure::read('Shop.payment_methods');
			$this->set(compact('orders', 'paymentMethods'));
		}

        function recive_payment(){
            $this->autoRender = false;

            $something['accepted']  = $this->params['url']['TransactionAccepted'];
            $something['Reference'] = $this->params['url']['Reference'];
            $something['Amount']    = $this->params['url']['Amount'];

            $this->log(serialize($this->params['url']), 'payment');

            if (!empty($something) && $something['accepted'] == true){
                $this->data['Payment']['order_id'] = $this->params['url']['Extra1'];
                $this->data['Payment']['user_id'] = $this->params['url']['Extra2'];
                $this->data['Payment']['payment_method_id'] = 3;
                $this->data['Payment']['amount'] = $this->params['url']['Amount'];

                if ($this->Payment->save($this->data)){
                    unset( $this->data );
                    $data['Order']['id'] = $this->params['url']['Extra1'];
                    $data['Order']['status_id'] = 2;
                    $this->Payment->Order->save($data);
                }
            }

            if (isset($this->params['url']['Extra1'])){
                $user = ClassRegistry::init( 'User.User' )->read(null, $this->params['url']['Extra2']);

                // @todo send email here about the payment

                $this->Session->setFlash(__( 'Payment has been credited and items are now being processed', true));
                $this->redirect(array('plugin' => 'sales', 'controller' => 'orders', 'action' => 'view', $this->params['url']['Extra1']));
            }
            else{
                $this->Session->setFlash(__('There was a problem with the payment, please contact admin', true));
                $this->redirect(array( 'plugin' => 'sales', 'controller' => 'orders'));
            }
        }

		function admin_index(){
			$year = $month = null;
			if(isset($this->Filter->filter['Order.year'])){
				$year  = $this->Filter->filter['Order.year'];
				unset($this->Filter->filter['Order.year']);
			}
			if(isset($this->Filter->filter['Order.month'])){
				$month = $this->Filter->filter['Order.month'];
				unset($this->Filter->filter['Order.month']);
			}

			$conditions = array();
			if($year || $month){
				if(!$year){
					$year = date('Y');
				}
				if(!$month){
					$month = date('m');
				}

				$startDate = $year.'-'.$month.'-01 00:00:00';
				$endDate   = $year.'-'.$month.'-'.date('t').' 23:59:59';
				$conditions = array('Order.created BETWEEN ? AND ?' => array($startDate, $endDate));
			}

			$this->paginate = array(
				'conditions' => $conditions,
				'contain' => array(
					'User',
					'Address',
					'Status'
				),
				'order' => array(
					'Status.ordering' => 'ASC'
				)
			);

			$orders = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$years = array_combine(range(2000, date('Y')), range(2000, date('Y')));
			rsort($years);
			$filterOptions['fields'] = array(
				'user_id' => $this->Order->User->find('list'),
				'status_id' => $this->Order->Status->find('list'),
				'payment_method' => Configure::read('Shop.payment_methods'),
				'shipping_method',
				'tracking_number',
				'year' => $years,
				'month' => array(
					'01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
					'04' => 'Apr', '05' => 'May', '06' => 'Jun',
					'07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
					'10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
				)
			);
			$this->set(compact('orders','filterOptions'));
		}

		function admin_view($id = null){
			if(!$id){
				$this->Session->setFlash(__('Please select an order to view'));
				$this->redirect($this->referer());
			}

			$order = $this->Order->find(
				'first',
				array(
					'conditions' => array(
						'Order.id' => $id
					),
					'contain' => array(
						'Item' => array(
							'Product'
						),
						'Status',
						'Payment'
					)
				)
			);

			$this->set(compact('order'));
		}
	}