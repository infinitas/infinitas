<?php
	/* Event Test cases generated on: 2010-12-14 22:12:39 : 1292364399*/
	App::uses('EventHelper', 'Events.View/Helper');
	App::uses('View', 'View');
	App::uses('Controller', 'Controller');

	class EventHelperTest extends CakeTestCase {
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Event = new EventHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Event);
	}

	public function testEvents() {
		$result = $this->Event->trigger('foo');
		$expected = array('foo'=> array());
		$this->assertEquals($expected, $result);

		$result = $this->Event->trigger('Plugin.foo');
		$this->assertEquals($expected, $result);

		$global = $this->Event->trigger('returnEventForTest');
		$plugin = $this->Event->trigger('Events.returnEventForTest');

		/**
			* test calling the plugin method vs global returns the same format.
			*/
		$this->assertSame(
			$global['returnEventForTest']['Events']->Handler,
			$plugin['returnEventForTest']['Events']->Handler
		);
		$this->assertSame($global['returnEventForTest']['Events']->Handler, $this->Event);
		$this->assertSame($plugin['returnEventForTest']['Events']->Handler, $this->Event);
	}
	
}