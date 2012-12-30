<?php
/**
 * ConfigsEventsTest
 *
 * These tests are extended from InfinitasEventTestCase which does most of the
 * automated testing for simple events
 */

App::uses('InfinitasEventTestCase', 'Events.Test/Lib');

class AssetsEventsTest extends InfinitasEventTestCase {

/**
 * test required js loads correctly
 */
	public function testRequireJavascript() {
		Configure::write('Assets.bootstrap', 0);
		$this->ViewObject->params = array();
		$expected = array('requireJavascriptToLoad' => array('Assets' => array(
			'Assets.3rd/jquery',
			'Assets.3rd/jquery_ui',
			'Assets.3rd/metadata',
			'Assets.3rd/load-image',
			'Assets.infinitas',
			'Assets.libs/core',
			'Assets.libs/form',
			'Assets.libs/html',
			'Assets.libs/number',
			'Assets.3rd/rater',
			'Assets.3rd/moving_boxes'
		)));
		$result = $this->Event->trigger($this->ViewObject, 'Assets.requireJavascriptToLoad');
		$this->assertEquals($expected, $result);

		$this->ViewObject->params = array(
			'admin' => true
		);
		$expected = array('requireJavascriptToLoad' => array('Assets' => array(
			'Assets.3rd/jquery',
			'Assets.3rd/jquery_ui',
			'Assets.3rd/metadata',
			'Assets.3rd/load-image',
			'Assets.infinitas',
			'Assets.libs/core',
			'Assets.libs/form',
			'Assets.libs/html',
			'Assets.libs/number',
			'Assets.3rd/date',
			'Assets.3rd/colorpicker',
			'Assets.libs/image_selector'
		)));
		$result = $this->Event->trigger($this->ViewObject, 'Assets.requireJavascriptToLoad');
		$this->assertEquals($expected, $result);
	}
}