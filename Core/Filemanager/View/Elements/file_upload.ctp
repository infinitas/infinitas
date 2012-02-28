<?php
	if(empty($fieldName)) {
		$fieldName = 'file';
	}
	
	if(isset($inputOptions)) {
		$inputOptions = array_merge(array('type' => 'file'), $inputOptions);
	}
	
	else {
		$inputOptions = array('type' => 'file');
	}
	
	echo $this->Form->input($fieldName, $inputOptions);