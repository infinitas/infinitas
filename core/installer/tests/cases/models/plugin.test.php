<?php
	App::import('lib', 'libs.test/AppModelTest');
	
	class TestPlugin extends AppModelTestCase {

		/**
		 * @brief Configuration for the test case
		 *
		 * Loading fixtures:
		 * 
		 * List all the needed fixtures in the do part of the fixture array.
		 * In replace you can overwrite fixtures of other plugins by your own.
		 *
		 * 'fixtures' => array(
		 *		'do' => array(
		 *			'SomePlugin.SomeModel
		 *		),
		 *		'replace' => array(
		 *			'Core.User' => 'SomePlugin.User
		 *		)
		 * )
		 * @var array 
		 */
		public $setup = array(
			'model' => 'Installer.Plugin',
			'fixtures' => array(
				'do' => array(
					'Migrations.SchemaMigration',
				)
			)
		);
		
		/**
		 * @brief Contains a list of test methods to run
		 *
		 * If it is set to false all the methods will run. Otherwise pass in an array
		 * with a list of tests to run.
		 *
		 * @var mixed 
		 */
		public $tests = false;

		/**
		 * @brief Tests Validation
		 *
		 * @test Enter description here
		 */
		public function testValidation() {
			
		}
		
		/**
		 * @test getting all plugins within the system
		 */
		public function testGetAllPlugins() {
			$plugins = App::objects('plugin');
			natsort($plugins);
			
			$this->assertEqual($plugins, $this->Plugin->getAllPlugins());
			$this->assertEqual($plugins, $this->Plugin->getAllPlugins('list'));
			$this->assertEqual($plugins, $this->Plugin->getAllPlugins('whatever'));
			
			$this->assertEqual(count(App::objects('plugin')), $this->Plugin->getAllPlugins('count'));
		}
		
		/**
		 * @test getting installed plugins
		 */
		public function testGetInstalledPlugins() {
			$this->disableBehavior('Trashable');
			
			$expected = array(
				'4cdc2083-430c-42f1-be78-235e6318cd70' => 'Backlinks',
				'4ce009fa-a76c-49f4-a488-120b6318cd70' => 'Blog',
				'4c94edcb-4468-4d97-87ad-78d86318cd70' => 'Categories',
				'4e286dc3-4454-4526-af0a-18876318cd70' => 'Charts',
				'4e286eaa-0364-49b2-b6f2-18876318cd70' => 'ClearCache',
				'4ce00a63-4e10-4cf2-b8d8-120b6318cd70' => 'Cms',
				'4c94edcb-9624-4ce3-acfe-78d86318cd70' => 'Comments',
				'4ce2a40a-5d8c-4157-b39e-133c6318cd70' => 'Configs',
				'4c94edcc-4ec0-4c4e-97de-78d86318cd70' => 'Contact',
				'4ce2a9b1-d348-4200-8828-15fb6318cd70' => 'Contents',
				'4cfe3fcf-600c-4ae2-b655-1bbd6318cd70' => 'Crons',
				'4c94edcc-ac70-474f-a4a3-78d86318cd70' => 'Data',
				'4cdc2397-f870-4615-ae65-247a6318cd70' => 'DebugKit',
				'4e286f53-c380-4930-8c94-18876318cd70' => 'Dev',
				'4cbc4e7e-b798-4aad-bc22-16836318cd70' => 'Emails',
				'4c94edcc-9b50-49e6-9af3-78d86318cd70' => 'Events',
				'4e286fd1-2a3c-4775-9a87-18876318cd70' => 'Facebook',
				'4cbc74a9-8fe8-43da-aaa4-20786318cd70' => 'Feed',
				'4c94edcc-aa7c-4509-9c18-78d86318cd70' => 'Filemanager',
				'4c94edcc-84ec-47c9-963d-78d86318cd70' => 'Filter',
				'4cdc264b-9d50-42b4-834b-2a206318cd70' => 'Gallery',
				'4e2871cd-90c0-431c-9eee-18876318cd70' => 'Geshi',
				'4c94edcb-3394-4e47-ad23-78d86318cd70' => 'Google',
				'4c94edcc-e084-44d8-bce8-78d86318cd70' => 'Installer',
				'4c94edcd-a188-49bb-8a08-78d86318cd70' => 'Libs',
				'4c94edcd-6834-4aed-913f-78d86318cd70' => 'Locks',
				'4c94edcd-cde8-4d91-b5d5-78d86318cd70' => 'Management',
				'4c94edcd-1014-4fd1-871c-78d86318cd70' => 'MeioUpload',
				'4c94edcd-a7d4-40dd-ae3e-78d86318cd70' => 'Menus',
				'4c94edcd-cd3c-4428-9327-78d86318cd70' => 'Migrations',
				'4c94edcd-ed34-4a23-b4e2-78d86318cd70' => 'Modules',
				'4c94edce-f07c-4564-bc30-78d86318cd70' => 'Newsletter',
				'4c94edce-fa1c-4abb-9ca6-78d86318cd70' => 'Routes',
				'4e287521-1b64-4feb-9e16-21136318cd70' => 'Security',
				'4cfe4015-e438-488f-95bb-1bbd6318cd70' => 'ServerStatus',
				'4cbd6788-2c28-4339-8917-47b96318cd70' => 'ShortUrls',
				'4c94edce-a440-453b-bcda-78d86318cd70' => 'Tags',
				'4c94edce-6ba4-4246-900f-78d86318cd70' => 'Themes',
				'4cdc2282-927c-46c3-81ee-247a6318cd70' => 'Thickbox',
				'4e28766e-9c54-40a6-818a-224c6318cd70' => 'Trash',
				'4c94edce-fba0-4fee-bb03-78d86318cd70' => 'Users',
				'4ce1695f-5488-4a9f-a5b8-1e806318cd70' => 'ViewCounter',
				'4c94edce-f8dc-4dd3-907f-78d86318cd70' => 'Webmaster',
				'4cdc22c5-c058-4049-9dc7-247a6318cd70' => 'WysiwygCkEditor',
				'4cdc2320-49b4-417a-9dc5-247a6318cd70' => 'WysiwygTinyMce',
				'4e28772d-3420-468b-8cf0-23506318cd70' => 'Xhprof',
			);
			$this->assertEqual($expected, $this->Plugin->getInstalledPlugins());
			$this->assertEqual($expected, $this->Plugin->getInstalledPlugins('list'));
			$this->assertEqual(46, $this->Plugin->getInstalledPlugins('count'));
			
			$this->assertTrue($this->Plugin->delete('4cdc2083-430c-42f1-be78-235e6318cd70'));
			$this->assertTrue($this->Plugin->delete('4ce009fa-a76c-49f4-a488-120b6318cd70'));
			$this->assertTrue($this->Plugin->delete('4c94edcb-4468-4d97-87ad-78d86318cd70'));
			$this->assertTrue($this->Plugin->delete('4e286dc3-4454-4526-af0a-18876318cd70'));
			$this->assertTrue($this->Plugin->delete('4e286eaa-0364-49b2-b6f2-18876318cd70'));
			$this->assertTrue($this->Plugin->delete('4ce00a63-4e10-4cf2-b8d8-120b6318cd70'));
			$this->assertTrue($this->Plugin->delete('4c94edcb-9624-4ce3-acfe-78d86318cd70'));
			$this->assertTrue($this->Plugin->delete('4ce2a40a-5d8c-4157-b39e-133c6318cd70'));
			$this->assertTrue($this->Plugin->delete('4c94edcc-4ec0-4c4e-97de-78d86318cd70'));
			$this->assertTrue($this->Plugin->delete('4ce2a9b1-d348-4200-8828-15fb6318cd70'));
			$this->assertTrue($this->Plugin->delete('4cfe3fcf-600c-4ae2-b655-1bbd6318cd70'));
			$this->assertTrue($this->Plugin->delete('4c94edcc-ac70-474f-a4a3-78d86318cd70'));
			$this->assertTrue($this->Plugin->delete('4cdc2397-f870-4615-ae65-247a6318cd70'));
			$this->assertTrue($this->Plugin->delete('4e286f53-c380-4930-8c94-18876318cd70'));
			$this->assertTrue($this->Plugin->delete('4cbc4e7e-b798-4aad-bc22-16836318cd70'));
			$this->assertTrue($this->Plugin->delete('4c94edcc-9b50-49e6-9af3-78d86318cd70'));
			$this->assertTrue($this->Plugin->delete('4e286fd1-2a3c-4775-9a87-18876318cd70'));
			$this->assertTrue($this->Plugin->delete('4cbc74a9-8fe8-43da-aaa4-20786318cd70'));
			$this->assertTrue($this->Plugin->delete('4c94edcc-aa7c-4509-9c18-78d86318cd70'));
			
			$expected = array(
				'4c94edcc-84ec-47c9-963d-78d86318cd70' => 'Filter',
				'4cdc264b-9d50-42b4-834b-2a206318cd70' => 'Gallery',
				'4e2871cd-90c0-431c-9eee-18876318cd70' => 'Geshi',
				'4c94edcb-3394-4e47-ad23-78d86318cd70' => 'Google',
				'4c94edcc-e084-44d8-bce8-78d86318cd70' => 'Installer',
				'4c94edcd-a188-49bb-8a08-78d86318cd70' => 'Libs',
				'4c94edcd-6834-4aed-913f-78d86318cd70' => 'Locks',
				'4c94edcd-cde8-4d91-b5d5-78d86318cd70' => 'Management',
				'4c94edcd-1014-4fd1-871c-78d86318cd70' => 'MeioUpload',
				'4c94edcd-a7d4-40dd-ae3e-78d86318cd70' => 'Menus',
				'4c94edcd-cd3c-4428-9327-78d86318cd70' => 'Migrations',
				'4c94edcd-ed34-4a23-b4e2-78d86318cd70' => 'Modules',
				'4c94edce-f07c-4564-bc30-78d86318cd70' => 'Newsletter',
				'4c94edce-fa1c-4abb-9ca6-78d86318cd70' => 'Routes',
				'4e287521-1b64-4feb-9e16-21136318cd70' => 'Security',
				'4cfe4015-e438-488f-95bb-1bbd6318cd70' => 'ServerStatus',
				'4cbd6788-2c28-4339-8917-47b96318cd70' => 'ShortUrls',
				'4c94edce-a440-453b-bcda-78d86318cd70' => 'Tags',
				'4c94edce-6ba4-4246-900f-78d86318cd70' => 'Themes',
				'4cdc2282-927c-46c3-81ee-247a6318cd70' => 'Thickbox',
				'4e28766e-9c54-40a6-818a-224c6318cd70' => 'Trash',
				'4c94edce-fba0-4fee-bb03-78d86318cd70' => 'Users',
				'4ce1695f-5488-4a9f-a5b8-1e806318cd70' => 'ViewCounter',
				'4c94edce-f8dc-4dd3-907f-78d86318cd70' => 'Webmaster',
				'4cdc22c5-c058-4049-9dc7-247a6318cd70' => 'WysiwygCkEditor',
				'4cdc2320-49b4-417a-9dc5-247a6318cd70' => 'WysiwygTinyMce',
				'4e28772d-3420-468b-8cf0-23506318cd70' => 'Xhprof',
			);
			$this->assertEqual($expected, $this->Plugin->getInstalledPlugins('list'));
			$this->assertEqual($expected, $this->Plugin->getInstalledPlugins('whatever'));
			$this->assertEqual(27, $this->Plugin->getInstalledPlugins('count'));
			
			$this->Plugin->deleteAll(array('Plugin.id' => array(
				'4c94edcc-84ec-47c9-963d-78d86318cd70', '4cdc264b-9d50-42b4-834b-2a206318cd70',
				'4e2871cd-90c0-431c-9eee-18876318cd70', '4c94edcb-3394-4e47-ad23-78d86318cd70',
				'4c94edcc-e084-44d8-bce8-78d86318cd70', '4c94edcd-a188-49bb-8a08-78d86318cd70',
				'4c94edcd-6834-4aed-913f-78d86318cd70', '4c94edcd-cde8-4d91-b5d5-78d86318cd70',
				'4c94edcd-1014-4fd1-871c-78d86318cd70', '4c94edcd-a7d4-40dd-ae3e-78d86318cd70',
				'4c94edcd-cd3c-4428-9327-78d86318cd70', '4c94edcd-ed34-4a23-b4e2-78d86318cd70',
				'4c94edce-f07c-4564-bc30-78d86318cd70', '4c94edce-fa1c-4abb-9ca6-78d86318cd70',
				'4e287521-1b64-4feb-9e16-21136318cd70', '4cfe4015-e438-488f-95bb-1bbd6318cd70',
				'4cbd6788-2c28-4339-8917-47b96318cd70', '4c94edce-a440-453b-bcda-78d86318cd70',
				'4c94edce-6ba4-4246-900f-78d86318cd70', '4cdc2282-927c-46c3-81ee-247a6318cd70',
				'4e28766e-9c54-40a6-818a-224c6318cd70', '4c94edce-fba0-4fee-bb03-78d86318cd70'
			)));
		}
		
		/**
		 * @test getting installed plugins
		 */
		public function testGetActiveInstalledPlugins() {
			$this->disableBehavior('Trashable');
			
			$expected = array(
				'4c94edcb-3394-4e47-ad23-78d86318cd70' => 'Google',
				'4c94edcb-4468-4d97-87ad-78d86318cd70' => 'Categories',
				'4c94edcb-9624-4ce3-acfe-78d86318cd70' => 'Comments',
				'4c94edcc-4ec0-4c4e-97de-78d86318cd70' => 'Contact',
				'4c94edcc-84ec-47c9-963d-78d86318cd70' => 'Filter',
				'4c94edcc-9b50-49e6-9af3-78d86318cd70' => 'Events',
				'4c94edcc-aa7c-4509-9c18-78d86318cd70' => 'Filemanager',
				'4c94edcc-ac70-474f-a4a3-78d86318cd70' => 'Data',
				'4c94edcc-e084-44d8-bce8-78d86318cd70' => 'Installer',
				'4c94edcd-1014-4fd1-871c-78d86318cd70' => 'MeioUpload',
				'4c94edcd-6834-4aed-913f-78d86318cd70' => 'Locks',
				'4c94edcd-a188-49bb-8a08-78d86318cd70' => 'Libs',
				'4c94edcd-a7d4-40dd-ae3e-78d86318cd70' => 'Menus',
				'4c94edcd-cd3c-4428-9327-78d86318cd70' => 'Migrations',
				'4c94edcd-cde8-4d91-b5d5-78d86318cd70' => 'Management',
				'4c94edcd-ed34-4a23-b4e2-78d86318cd70' => 'Modules',
				'4c94edce-6ba4-4246-900f-78d86318cd70' => 'Themes',
				'4c94edce-a440-453b-bcda-78d86318cd70' => 'Tags',
				'4c94edce-f07c-4564-bc30-78d86318cd70' => 'Newsletter',
				'4c94edce-f8dc-4dd3-907f-78d86318cd70' => 'Webmaster',
				'4c94edce-fa1c-4abb-9ca6-78d86318cd70' => 'Routes',
				'4c94edce-fba0-4fee-bb03-78d86318cd70' => 'Users',
				'4cbc4e7e-b798-4aad-bc22-16836318cd70' => 'Emails',
				'4cbc74a9-8fe8-43da-aaa4-20786318cd70' => 'Feed',
				'4cbd6788-2c28-4339-8917-47b96318cd70' => 'ShortUrls',
				'4cdc2083-430c-42f1-be78-235e6318cd70' => 'Backlinks',
				'4cdc2282-927c-46c3-81ee-247a6318cd70' => 'Thickbox',
				'4cdc22c5-c058-4049-9dc7-247a6318cd70' => 'WysiwygCkEditor',
				'4cdc2320-49b4-417a-9dc5-247a6318cd70' => 'WysiwygTinyMce',
				'4cdc2397-f870-4615-ae65-247a6318cd70' => 'DebugKit',
				'4cdc264b-9d50-42b4-834b-2a206318cd70' => 'Gallery',
				'4ce009fa-a76c-49f4-a488-120b6318cd70' => 'Blog',
				'4ce00a63-4e10-4cf2-b8d8-120b6318cd70' => 'Cms',
				'4ce1695f-5488-4a9f-a5b8-1e806318cd70' => 'ViewCounter',
				'4ce2a40a-5d8c-4157-b39e-133c6318cd70' => 'Configs',
				'4ce2a9b1-d348-4200-8828-15fb6318cd70' => 'Contents',
				'4cfe3fcf-600c-4ae2-b655-1bbd6318cd70' => 'Crons',
				'4cfe4015-e438-488f-95bb-1bbd6318cd70' => 'ServerStatus',
				'4e286dc3-4454-4526-af0a-18876318cd70' => 'Charts',
				'4e286eaa-0364-49b2-b6f2-18876318cd70' => 'ClearCache',
				'4e286f53-c380-4930-8c94-18876318cd70' => 'Dev',
				'4e286fd1-2a3c-4775-9a87-18876318cd70' => 'Facebook',
				'4e2871cd-90c0-431c-9eee-18876318cd70' => 'Geshi',
				'4e287521-1b64-4feb-9e16-21136318cd70' => 'Security',
			);
			$this->assertEqual($expected, $this->Plugin->getActiveInstalledPlugins());
			$this->assertEqual($expected, $this->Plugin->getActiveInstalledPlugins('list'));
			$this->assertEqual($expected, $this->Plugin->getActiveInstalledPlugins('whatever'));
			$this->assertEqual(44, $this->Plugin->getActiveInstalledPlugins('count'));
			
			$this->assertTrue(
				$this->Plugin->updateAll(
					array('active' => 0),
					array('Plugin.id' => array('4c94edcc-9b50-49e6-9af3-78d86318cd70', '4e286fd1-2a3c-4775-9a87-18876318cd70', '4cbc74a9-8fe8-43da-aaa4-20786318cd70', '4c94edcc-aa7c-4509-9c18-78d86318cd70'))
				)
			);
			$this->assertEqual(43, $this->Plugin->getActiveInstalledPlugins('count'));
		}
		
		/**
		 * @test get plugins that are not installed
		 */
		public function testGetNonInstalledPlugins() {
			$this->disableBehavior('Trashable');
			
			$notInstalled = array_values($this->Plugin->getNonInstalledPlugins());
			$this->Plugin->delete('4cdc2282-927c-46c3-81ee-247a6318cd70');
			
			$notInstalled2 = array_values($this->Plugin->getNonInstalledPlugins());
			$this->assertIdentical(count($notInstalled) + 1, count($notInstalled2));
			$this->assertIdentical(count($notInstalled2), $this->Plugin->getNonInstalledPlugins('count'));
			
			$this->assertEqual('Thickbox', current(array_diff($notInstalled2, $notInstalled)));
		}
		
		/**
		 * @test checking if a plugin is installed
		 */
		public function testIsInstalled() {
			$this->disableBehavior('Trashable');
			
			$this->assertFalse($this->Plugin->isInstalled());
			$this->assertFalse($this->Plugin->isInstalled(false));
			$this->assertFalse($this->Plugin->isInstalled('fake-plugin'));
			
			$this->assertTrue($this->Plugin->isInstalled('Trash'));
			$this->assertTrue($this->Plugin->isInstalled('trash'));
			
			$this->assertTrue($this->Plugin->isInstalled('ClearCache'));
			$this->assertTrue($this->Plugin->isInstalled('clear_cache'));
			
			$this->Plugin->delete('4e286eaa-0364-49b2-b6f2-18876318cd70');	
			
			$this->assertFalse($this->Plugin->isInstalled('ClearCache'));
			$this->assertFalse($this->Plugin->isInstalled('clear_cache'));		
		}
		
		/**
		 * @brief you should have all the supported plugins available for testing plugins
		 * @test installing a plugin
		 */
		public function testInstallPlugin() {
			$SchemaMigration = ClassRegistry::init('SchemaMigration');
					
			$this->assertTrue($this->Plugin->installPlugin('Twitter'));
			$this->assertEqual('Twitter', $this->Plugin->field('name'));
			
			$this->assertTrue($this->Plugin->installPlugin('Blog'));
			$this->assertEqual('Blog', $this->Plugin->field('name'));
			
			$count = $SchemaMigration->find('count');
			$this->assertTrue($this->Plugin->installPlugin('Blog'));
			$this->assertEqual('Blog', $this->Plugin->field('name'));
			$this->assertIdentical($count, $SchemaMigration->find('count'));
			
			$this->assertTrue($this->Plugin->installPlugin('Locks'));
		}
	}