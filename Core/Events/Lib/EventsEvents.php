<?php
	final class EventsEvents extends AppEvents {		
		public function onRequireHelpersToLoad($event = null) {
			return array(
				'Events.Event' => true
			);
		}

		public function onRequireComponentsToLoad($event = null) {
			return 'Events.Event';
		}

		public function onReturnEventForTest($event) {
			return $event;
		}
	}