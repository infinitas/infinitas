<?php

	if($this->params['controller'] !== 'specials'){
		echo $this->element('specials', array('plugin' => 'shop'));
	}

	if($this->params['controller'] !== 'spotlights'){
		echo $this->element('spotlights', array('plugin' => 'shop'));
	}