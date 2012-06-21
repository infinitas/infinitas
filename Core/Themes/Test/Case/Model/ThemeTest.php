<?php
	/* Theme Test cases generated on: 2010-03-13 11:03:20 : 1268471240*/
	App::uses('Theme', 'Themes.Model');
	
	class ThemeTest extends CakeTestCase {
		public $fixtures = array(
			'plugin.themes.theme',
			'plugin.trash.core_trash',
		);

		public function startTest() {
			$this->Theme = ClassRegistry::init('Themes.Theme');
			$this->Theme->Behaviors->detach('Trashable');
		}

		public function endTest() {
			unset($this->Theme);
			ClassRegistry::flush();
		}

		public function testGetting() {
			$expected['Theme'] = array(
				'id' => 4,
				'name' => 'aqueous_light',
				'default_layout' => null,
				'core' => false
			);
			$this->assertEqual($expected, $this->Theme->getCurrentTheme());
		}
		
		public function testSave() {
			$result = $this->Theme->save(array('id' => 1, 'active' => true));
			unset($result['Theme']['modified']);
			$expected = array('Theme' => array('id' => 1, 'active' => true));
			$this->assertEqual($result, $expected);
			
			$result = $this->Theme->find('all');
			$this->assertEqual($result[0]['Theme']['id'], 1);
			$this->assertTrue($result[0]['Theme']['active']);
			$this->assertFalse($result[1]['Theme']['active']);
			$this->assertFalse($result[2]['Theme']['active']);
			$this->assertFalse($result[3]['Theme']['active']);
			
			$result = $this->Theme->save(array('id' => 2, 'active' => false));
			unset($result['Theme']['modified']);
			$expected = array('Theme' => array('id' => 2, 'active' => false));
			$this->assertEqual($result, $expected);
			
			$result = $this->Theme->find('all');
			$this->assertEqual($result[0]['Theme']['id'], 1);
			$this->assertTrue($result[0]['Theme']['active']);
			$this->assertFalse($result[1]['Theme']['active']);
			$this->assertFalse($result[2]['Theme']['active']);
			$this->assertFalse($result[3]['Theme']['active']);
		}

		public function testKeepingOneActiveThemeAtATime() {
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
		
		public function testDeactivateAll() {
			$result = $this->Theme->find('count', array('conditions' => array('active' => 1)));
			$this->assertEqual($result, 1);
			
			$this->Theme->deactivateAll();
			
			$result = $this->Theme->find('count', array('conditions' => array('active' => 1)));
			$this->assertEqual($result, 0);
		}
		
		public function testInstalled() {
			$result = $this->Theme->installed();
			$expected = array(
				'default' => 'Default', 
				'terrafirma' => 'Terrafirma', 
				'aqueous' => 'Aqueous', 
				'aqueous_light' => 'Aqueous Light'
			);
			$this->assertEqual($result, $expected);
		}
		
		public function testNotInstalled() {
			//$result = $this->Theme->notInstalled();
		}
		
		public function testDelete() {
			$this->assertFalse($this->Theme->delete(4));
			$this->assertTrue($this->Theme->delete(1));
		}
	}