<?php
class EventComponent extends Object
{
	/**
	 * Controller Instance
	 * @var object
	 */
	public $Controler = null;

	/**
	 * Startup
	 *
	 * @param object $controller
	 *
	 */
    public function initialize(&$Controller)
	{
		$this->Controller =& $Controller;
	}
	
	/**
	 * Trigger a event
	 * 
	 * @param string $eventName Name of the event to trigger
	 * @param array $data Data to pass to event handler
	 * @return array:
	 */
	public function trigger($eventName, $data = array())
	{
		return EventCore::trigger($this->Controller, $eventName, $data);
	}
}