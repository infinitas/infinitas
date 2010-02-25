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
	
	public function trigger($eventName, $data = array())
	{
		return EventCore::trigger($this->Controller, $eventName, $data);
	}
}