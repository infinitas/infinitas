<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('InfinitasHelper', 'Libs.View/Helper');

/**
 * InfinitasHelper Test Case
 *
 */
class InfinitasHelperTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.management.ticket',
		'plugin.users.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Infinitas = new InfinitasHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Infinitas);

		parent::tearDown();
	}

/**
 * @brief test status icon
 *
 * @dataProvider statusDataProvider
 */
	public function testStatus($data, $expected) {
		$result = $this->Infinitas->status($data['status'], $data['options']);
		$this->assertTags($result, $expected);
	}

/**
 * @brief data provider for testing status
 */
	public function statusDataProvider() {
		return array(
			'basic_yes' => array(
				array('status' => 1, 'options' => array()),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/active.png',
						'class' => 'icon-status',
						'title' => 'Status :: This record is active',
						'alt' => 'On'
					))
				)
			),
			'custom_yes' => array(
				array('status' => 1, 'options' => array('title_yes' => 'foo bar')),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/active.png',
						'class' => 'icon-status',
						'title' => 'foo bar',
						'alt' => 'On'
					))
				)
			),
			'basic_no' => array(
				array('status' => 0, 'options' => array()),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'class' => 'icon-status',
						'title' => 'Status :: This record is disabled',
						'alt' => 'Off'
					))
				)
			),
			'custom_no' => array(
				array('status' => 0, 'options' => array('title_no' => 'foo bar')),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'class' => 'icon-status',
						'title' => 'foo bar',
						'alt' => 'Off'
					))
				)
			)
		);
	}

/**
 * @brief test mass action check box
 *
 * @dataProvider massActionCheckBoxDataProvider
 */
	public function testMassActionCheckBox($data, $expected) {
		$this->Infinitas->request->params['models'] = array('User' => array('plugin' => 'Users', 'model' => 'User'));
		$result = $this->Infinitas->massActionCheckBox($data['data'], $data['options']);
		$this->assertTags($result, $expected);
	}

/**
 * @brief data provider for mass action checkbox tests
 *
 * @return array
 */
	public function massActionCheckBoxDataProvider() {
		return array(
			'basic' => array(
				array(
					'data' => array(
						'User' => array(
							'id' => 123
						)
					),
					'options' => array(

					)),
				array(
					array('input' => array(
						'type' => 'hidden',
						'name' => 'data[User][0][massCheckBox]',
						'id' => 'User0MassCheckBox_',
						'value' => 0
					)),
					array('input' => array(
						'type' => 'checkbox',
						'name' => 'data[User][0][massCheckBox]',
						'id' => 'User0MassCheckBox',
						'value' => 123
					)),
				)
			),
			'hidden' => array(
				array(
					'data' => array(
						'User' => array(
							'id' => 123
						)
					),
					'options' => array(
						'hidden' => true
					)),
				array(
					array('input' => array(
						'type' => 'hidden',
						'name' => 'data[User][0][massCheckBox]',
						'id' => 'User0MassCheckBox_',
						'value' => 0
					)),
					array('input' => array(
						'type' => 'checkbox',
						'hidden' => 1,
						'name' => 'data[User][0][massCheckBox]',
						'id' => 'User0MassCheckBox',
						'value' => 123
					)),
				)
			),
			'checked' => array(
				array(
					'data' => array(
						'User' => array(
							'id' => 123
						)
					),
					'options' => array(
						'checked' => true
					)),
				array(
					array('input' => array(
						'type' => 'hidden',
						'name' => 'data[User][0][massCheckBox]',
						'id' => 'User0MassCheckBox_',
						'value' => 0
					)),
					array('input' => array(
						'type' => 'checkbox',
						'checked' => 'checked',
						'name' => 'data[User][0][massCheckBox]',
						'id' => 'User0MassCheckBox',
						'value' => 123
					)),
				)
			),
			'aliased' => array(
				array(
					'data' => array(
						'MyUser' => array(
							'id' => 123
						)
					),
					'options' => array(
						'alias' => 'MyUser'
					)),
				array(
					array('input' => array(
						'type' => 'hidden',
						'name' => 'data[MyUser][0][massCheckBox]',
						'id' => 'MyUser0MassCheckBox_',
						'value' => 0
					)),
					array('input' => array(
						'type' => 'checkbox',
						'name' => 'data[MyUser][0][massCheckBox]',
						'id' => 'MyUser0MassCheckBox',
						'value' => 123
					)),
				)
			),
			'missing_data' => array(
				array(
					'data' => array(
						'MyUser' => array(
							'id' => 123
						)
					),
					'options' => array(
					)),
				array()
			)
		);
	}

}
