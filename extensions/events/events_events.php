<?php
	final class EventsEvents extends AppEvents {
		public function onRequireHelpersToLoad(){
			return 'Events.Event';
		}
	}