<?php
	if(empty($fieldName)) {
		$fieldName = 'file';
	}
	
	echo $this->Form->input($fieldName, array('type' => 'file'));