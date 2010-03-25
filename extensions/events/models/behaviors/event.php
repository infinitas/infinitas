<?php
class EventBehavior extends ModelBehavior {
	var $name = 'Events';

	/**
	 * Trigger a event
	 *
	 * @param string $eventName Name of the event to trigger
	 * @param array $data Data to pass to event handler
	 * @return array:
	 */
	public function triggerEvent(&$Model, $eventName, $data = array()){
		return EventCore::trigger($Model, $eventName, $data);
	}
}