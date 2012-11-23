<?php
App::uses('Theme', 'Themes.Model');

class ThemeTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.themes.theme',
		'plugin.trash.trash',
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Theme = ClassRegistry::init('Themes.Theme');
		$this->Theme->Behaviors->detach('Trashable');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Theme);
	}

/**
 * test getting
 */
	public function testGetting() {
		$expected['Theme'] = array(
			'id' => 1,
			'name' => 'default',
			'default_layout' => null
		);
		$this->assertEqual($expected, $this->Theme->find('currentTheme', array(
			'admin' => false
		)));

		$expected['Theme'] = array(
			'id' => 4,
			'name' => 'aqueous_light',
			'default_layout' => null
		);
		$this->assertEqual($expected, $this->Theme->find('currentTheme', array(
			'admin' => true
		)));
	}

/**
 * test getting exception
 *
 * @expectedException NoThemeSelectedException
 */
	public function testGettingException() {
		$this->Theme->find('currentTheme');
	}

/**
 * test getting exception
 *
 * @expectedException NoThemeConfiguredException
 */
	public function testGettingEmptyException() {
		$this->Theme->find('currentTheme', array(
			'admin' => true,
			'conditions' => array(
				'Theme.id' => 123
			)
		));
	}

/**
 * test save
 */
	public function testSave() {
		$result = $this->Theme->save(array('id' => 1, 'admin' => true));
		unset($result['Theme']['modified']);
		$expected = array('Theme' => array('id' => 1, 'admin' => true));
		$this->assertEqual($result, $expected);

		$result = $this->Theme->find('all');
		$this->assertEqual($result[0]['Theme']['id'], 1);
		$this->assertTrue($result[0]['Theme']['admin']);
		$this->assertFalse($result[1]['Theme']['admin']);
		$this->assertFalse($result[2]['Theme']['admin']);
		$this->assertFalse($result[3]['Theme']['admin']);

		$result = $this->Theme->save(array('id' => 1, 'frontend' => true));
		unset($result['Theme']['modified']);
		$expected = array('Theme' => array('id' => 1, 'frontend' => true));
		$this->assertEqual($result, $expected);

		$result = $this->Theme->find('all');
		$this->assertEqual($result[0]['Theme']['id'], 1);
		$this->assertTrue($result[0]['Theme']['admin']);
		$this->assertFalse($result[1]['Theme']['admin']);
		$this->assertFalse($result[2]['Theme']['admin']);
		$this->assertFalse($result[3]['Theme']['admin']);
		
		$this->assertEqual($result[0]['Theme']['id'], 1);
		$this->assertTrue($result[0]['Theme']['frontend']);
		$this->assertFalse($result[1]['Theme']['frontend']);
		$this->assertFalse($result[2]['Theme']['frontend']);
		$this->assertFalse($result[3]['Theme']['frontend']);

		$result = $this->Theme->save(array('id' => 2, 'admin' => false));
		unset($result['Theme']['modified']);
		$expected = array('Theme' => array('id' => 2, 'admin' => false));
		$this->assertEqual($result, $expected);

		$result = $this->Theme->find('all');
		$this->assertEqual($result[0]['Theme']['id'], 1);
		$this->assertTrue($result[0]['Theme']['admin']);
		$this->assertFalse($result[1]['Theme']['admin']);
		$this->assertFalse($result[2]['Theme']['admin']);
		$this->assertFalse($result[3]['Theme']['admin']);
	}

/**
 * test keeping one type of theme active
 */
	public function testKeepingOneTypeOfThemeAtATime() {
		// adding a new theme that is admin should be the only admin one
		$theme = $this->Theme->find('first', array('conditions' => array('Theme.admin' => 0)));
		unset($theme['Theme']['id']);
		$theme['Theme']['admin'] = 1;
		$theme['Theme']['name'] = 'some new theme';
		$this->Theme->save($theme);
		$result = $this->Theme->find('count', array('conditions' => array('Theme.admin' => 1)));
		$expected = 1;
		$this->assertEquals($expected, $result);

		// making a theme admin should be the only admin one
		$theme = $this->Theme->find('first', array('conditions' => array('Theme.admin' => 0)));
		$theme['Theme']['admin'] = 1;
		$this->Theme->save($theme);
		$result = $this->Theme->find('count', array('conditions' => array('Theme.admin' => 1)));
		$expected = 1;
		$this->assertEquals($expected, $result);

		// deleteing an admin theme should set another to admin
		$this->Theme->delete($theme['Theme']['id']);
		$result = $this->Theme->find('count', array('conditions' => array('Theme.admin' => 1)));
		$expected = 1;
		$this->assertEquals($expected, $result);
	}

/**
 * test deactivate all
 */
	public function testDeactivateAll() {
		$result = $this->Theme->find('count', array('conditions' => array('admin' => 1)));
		$this->assertEqual($result, 1);

		$this->Theme->deactivateAll('admin');

		$result = $this->Theme->find('count', array('conditions' => array('admin' => 1)));
		$this->assertEqual($result, 0);
	}

/**
 * test installed
 */
	public function testInstalled() {
		$result = $this->Theme->find('installed');
		$expected = array(
			'default' => 'Default',
			'terrafirma' => 'Terrafirma',
			'aqueous' => 'Aqueous',
			'aqueous_light' => 'Aqueous Light'
		);
		$this->assertEqual($result, $expected);
	}

/**
 * test not installed
 */
	public function testNotInstalled() {
		//$result = $this->Theme->notInstalled();
	}

/**
 * test delete
 */
	public function testDelete() {
		$this->assertFalse($this->Theme->delete(1));
		$this->assertFalse($this->Theme->delete(4));

		$this->assertTrue($this->Theme->delete(2));
		$this->assertTrue($this->Theme->delete(3));
	}

}