<?php
App::uses('InfinitasEventTestCase', 'Events.Test/Lib');

class UsersEventsTest extends InfinitasEventTestCase {

	public function testOnSetupRoutes() {
		EventCore::trigger($this, 'Users.setupRoutes');
	}
}