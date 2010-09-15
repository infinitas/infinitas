<?php
	final class EventsEvents extends AppEvents {
		public function onAttachBehaviors(){
			if(is_subclass_of($event->Handler, 'Model')){
				$event->Handler->Behaviors->attach('Events.Event');
			}
		}
		
		public function onRequireHelpersToLoad(){
			return 'Events.Event';
		}

		public function onRequireComponentsToLoad(){
			return 'Events.Event';
		}
	}