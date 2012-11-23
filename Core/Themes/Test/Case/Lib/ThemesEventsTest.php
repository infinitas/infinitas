<?php
/**
 * ThemesEventsTest
 *
 * @package Infinitas.Themes.Test
 */

App::uses('ThemesEvents', 'Themes.Lib');
App::uses('InfinitasEventTestCase', 'Events.Test/Lib');

/**
 * ThemesEventsTest
 *
 * @package Infinitas.Themes.Test
 */

class ThemesEventsTest extends InfinitasEventTestCase {
/**
 * test installer theme
 */
	public function testInstallerTheme() {
		$expected = $this->_manualCall('installerTheme', $this->ObjectEvent);

		$result = $this->Event->trigger($this->ModelObject, $this->plugin . '.installerTheme');
		$this->assertEquals($expected, $result);
	}

}