<?php
	final class SecurityEvents extends AppEvents {
		public function onRequireComponentsToLoad($event = null) {
			return array(
				'Security.InfinitasSecurity'
			);
		}
	}
