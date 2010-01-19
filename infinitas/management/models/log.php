<?php
	class Log extends ManagementAppModel {
		var $tablePrefix = 'core_';
		var $name = 'Log';

		var $order = array(
			'created' => 'DESC'
		);
	}
?>