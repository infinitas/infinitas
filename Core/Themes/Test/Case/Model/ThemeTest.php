<?php
	/* Theme Test cases generated on: 2010-03-13 11:03:20 : 1268471240*/
	App::uses('Theme', 'Themes.Model');

	class TestTheme extends Theme{
		public $alias = 'Theme';
		public $useDbConfig = 'test';
		public $hasMany = array();
	}

	class ThemeTest extends CakeTestCase {
		public $fixtures = array(
			'plugin.themes.theme',
		);

		function startTest() {
			$this->Theme = new TestTheme();
		}

		function testGetting(){
			$expected['Theme'] = array(
				'id' => 4,
				'name' => 'aqueous_light',
				'core' => 0
			);
			$this->assertEqual($expected, $this->Theme->getCurrentTheme());
		}

		function testKeepingOneActiveThemeAtATime(){
			// adding a new theme that is active should be the only active one
			$theme = $this->Theme->find('first', array('conditions' => array('Theme.active' => 0)));
			unset($theme['Theme']['id']);
			$theme['Theme']['active'] = 1;
			$theme['Theme']['name'] = 'some new theme';
			$this->Theme->save($theme);
			$result = $this->Theme->find('count', array('conditions' => array('Theme.active' => 1)));
			$expected = 1;
			$this->assertEquals($expected, $result);

			// making a theme active should be the only active one
			$theme = $this->Theme->find('first', array('conditions' => array('Theme.active' => 0)));
			$theme['Theme']['active'] = 1;
			$this->Theme->save($theme);
			$result = $this->Theme->find('count', array('conditions' => array('Theme.active' => 1)));
			$expected = 1;
			$this->assertEquals($expected, $result);

			// deleteing an active theme should set another to active
			$this->Theme->delete($theme['Theme']['id']);
			$result = $this->Theme->find('count', array('conditions' => array('Theme.active' => 1)));
			$expected = 1;
			$this->assertEquals($expected, $result);
		}

		function endTest() {
			unset($this->Theme);
			ClassRegistry::flush();
		}
	}