<?php
	class CronsEvents extends AppEvents{
		public function onSetupConfig($event, $data = null) {
			Configure::load('crons.config');
		}
	}