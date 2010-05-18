<?php
	class ItemsController extends OrderAppController{
		var $name = 'Items';

		function admin_index(){
			$year = $month = null;
			if(isset($this->Filter->filter['Item.year'])){
				$year  = $this->Filter->filter['Item.year'];
				unset($this->Filter->filter['Item.year']);
			}
			if(isset($this->Filter->filter['Item.month'])){
				$month = $this->Filter->filter['Item.month'];
				unset($this->Filter->filter['Item.month']);
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
				$conditions = array('Item.created BETWEEN ? AND ?' => array($startDate, $endDate));
			}

			$this->paginate = array(
				'conditions' => $conditions,
				'contain' => array(
					'Product',
					'Order'
				)
			);

			$items = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$years = array_combine(range(2000, date('Y')), range(2000, date('Y')));
			rsort($years);
			$filterOptions['fields'] = array(
				'order_id' => $this->Item->Order->find('list'),
				'product_id' => $this->Item->Product->find('list'),
				'year' => $years,
				'month' => array(
					'01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
					'04' => 'Apr', '05' => 'May', '06' => 'Jun',
					'07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
					'10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
				)
			);
			$this->set(compact('items','filterOptions'));
		}
	}