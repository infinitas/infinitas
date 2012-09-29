<?php
App::uses('InfinitasEventTestCase', 'Events.Test/Lib');
class AssetsEventsTest extends InfinitasEventTestCase {
/**
 *@brief test required helpers load correctly
 */
	public function testRequireHelpers() {
		$this->ViewObject->params = array();
		$expected = array('requireHelpersToLoad' => array('Assets' => array('Assets.Compress')));
		$result = $this->Event->trigger($this->ViewObject, 'Assets.requireHelpersToLoad');
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test required js loads correctly
 */
	public function testRequireJavascript() {
		$this->ViewObject->params = array();
		$expected = array('requireJavascriptToLoad' => array('Assets' => array(
			'Assets.3rd/jquery',
			'Assets.3rd/jquery_ui',
			'Assets.3rd/metadata',
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
			'Assets.infinitas',
			'Assets.libs/core',
			'Assets.libs/form',
			'Assets.libs/html',
			'Assets.libs/number',
			'Assets.3rd/date',
			'Assets.3rd/image_drop_down'
		)));
		$result = $this->Event->trigger($this->ViewObject, 'Assets.requireJavascriptToLoad');
		$this->assertEquals($expected, $result);
	}
}