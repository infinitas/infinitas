<?php
	final class EventsEvents {
		public function onTestEvent($event){
			echo('Success!');
		}

		public function onRequireHelpersToLoad(){
			return 'Events.Event';
		}
	}