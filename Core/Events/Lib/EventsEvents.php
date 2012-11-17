<?php
class EventsEvents extends AppEvents {
	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Events.Event' => true
		);
	}

	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'Events.Event'
		);
	}

	public function onReturnEventForTest(Event $Event) {
		return $Event;
	}
}