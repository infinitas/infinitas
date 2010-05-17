<?php
	class OrderEvents{
		function onSetupCache(){
			return array(
				'name' => 'orders',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'orders.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		function onUserLogin(&$event, $data){
			// check if order status has changed and notify
		}
	}