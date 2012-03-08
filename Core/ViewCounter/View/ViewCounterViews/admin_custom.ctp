<?php
    echo $this->Form->create('ViewCount', array('action' => 'reports'));
        $massActions = $this->Infinitas->massActionButtons(array('export'));
		echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
	$this->Form->end();