<?php
	echo $this->Form->create('Cart');
		foreach(Configure::read('Shop.shipping_methods') as $type){
			$types[$type] = Inflector::humanize($type);
		}

		echo $this->Form->input('shipping_method', array('options' => $types));
	echo $this->Form->end('Submit');