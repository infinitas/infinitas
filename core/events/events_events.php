<?php
	final class EventsEvents extends AppEvents {		
		public function onRequireHelpersToLoad(){
			return 'Events.Event';
		}

		public function onRequireComponentsToLoad(){
			return 'Events.Event';
		}
	}