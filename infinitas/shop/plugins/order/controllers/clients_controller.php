<?php
	class ClientsController extends OrderAppController{
		var $name = 'Clients';

		var $uses = false;

		function index(){
			$this->loadModel('Order.Order');
			$pendingOrders = $this->Order->getPendingOrders($this->Session->read('Auth.User.id'));
			$this->set(compact('pendingOrders'));
		}

		function addresses(){
			$this->loadModel('Management.Address');
			$addresses = $this->Address->getAddressByUser($this->Session->read('Auth.User.id'), 'all');
			$this->set(compact('addresses'));
		}
	}