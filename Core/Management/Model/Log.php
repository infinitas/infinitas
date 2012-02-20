<?php
	class Log extends ManagementAppModel {
		public $useTable = 'core_logs';
		public $order = array(
			'created' => 'DESC'
		);
	}