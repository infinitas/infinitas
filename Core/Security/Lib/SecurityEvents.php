<?php
	final class SecurityEvents extends AppEvents{
		public function onSetupConfig($event, $data = null) {
			Configure::load('Security.config');
		}

		public function onRequireComponentsToLoad($event = null) {
			return array(
				'Security.InfinitasSecurity'
			);
		}
	}
