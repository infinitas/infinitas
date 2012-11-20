<?php
    echo $this->Form->create('ViewCount', array('action' => 'reports'));
		echo $this->Infinitas->adminIndexHead($filterOptions, array(
			'export'
		));
	$this->Form->end();