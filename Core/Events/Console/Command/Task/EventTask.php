<?php
/**
 * EventTask
 *
 * @package Infinitas.Events.Console.Task
 */

/**
 * EventTask
 *
 * The event task provides easy access to the event system in console scripts
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Events.Console.Task
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class EventTask extends AppShell {
/**
 * Trigger a event
 *
 * @param string $eventName Name of the event to trigger
 * @param array $data Data to pass to event handler
 *
 * @return array
 */
	public function trigger($eventName, $data = array()) {
		return EventCore::trigger($this, $eventName, $data);
	}

/**
 * Get a list of plugins with the passed in event name
 *
 * @param string $eventName the event name to look up
 *
 * @return array
 */
	public function pluginsWith($eventName) {
		return EventCore::pluginsWith($eventName);
	}
}