<?php
	final class EventsEvents extends AppEvents {		
		public function onRequireHelpersToLoad(){
			return array(
				'Events.Event' => true
			);
		}

		public function onRequireComponentsToLoad(){
			return 'Events.Event';
		}
	}