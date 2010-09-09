<?php
	$showMostViewed =
		$this->params['controller'] == 'products' &&
		$this->params['action'] == 'index' &&
		(isset($this->params['named']['sort']) && $this->params['named']['sort'] == 'views');

	if(!$showMostViewed){
		echo $this->element('most_viewed_products', array('plugin' => 'shop'));
	}

	$showNewest =
		$this->params['controller'] == 'products' &&
		$this->params['action'] == 'index' &&
		(isset($this->params['named']['sort']) && $this->params['named']['sort'] == 'created');

	if(!$showNewest){
		echo $this->element('newest', array('plugin' => 'shop'));
	}