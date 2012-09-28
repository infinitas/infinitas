<?php
App::uses('TicketableBehavior', 'Libs.Model/Behavior');

/**
 * TicketableBehavior Test Case
 *
 */
class TicketableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Ticketable = new TicketableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Ticketable);

		parent::tearDown();
	}

/**
 * testCreateTicket method
 *
 * @return void
 */
	public function testCreateTicket() {
	}

/**
 * testGetTicket method
 *
 * @return void
 */
	public function testGetTicket() {
	}

}
