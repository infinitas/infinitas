<?php
class InstallerTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.installer.plugin',
		'plugin.migrations.schema_migration',
		'plugin.blog.blog_post'
	);

	public function setUp() {
		parent::setUp();
		$this->Plugin = ClassRegistry::init('Installer.Plugin');
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Plugin);
	}

/**
 * @test some random methods in the install lib
 */
	public function testInstallerMethods() {
		App::uses('InstallerLib', 'Installer.Lib');
		$Installer = new InstallerLib();

		$data = $Installer->getLicense();
		$this->assertFalse(empty($data));
		$this->assertEqual($data, $Installer->getLicense('html'));
		$this->assertEqual($data, $Installer->getLicense('fake'));
		$this->assertEqual($Installer->getLicense('text'), strip_tags($Installer->getLicense('text')));

		$data = $Installer->getWelcome();
		$this->assertFalse(empty($data));
		$this->assertEqual($data, $Installer->getWelcome('html'));
		$this->assertEqual($data, $Installer->getWelcome('fakse'));
		$this->assertEqual($Installer->getWelcome('text'), strip_tags($Installer->getWelcome('text')));

		// needs to be mocked
		// $this->assertTrue(count($Installer->getSupportedDbs()) == 3);
		// $this->assertTrue(count($Installer->getSupportedDbs(false)) == 3);

		$connection = array(
			'connection' => $this->db->config
		);
		unset($connection['connection']['prefix']);
		$expected = $connection['connection'];
		if(empty($expected['port'])) {
			unset($expected['port']);
		}

		$cleanedConnection = $Installer->cleanConnectionDetails($connection);
		$this->assertEqual($expected, $cleanedConnection);
		$this->assertTrue((bool)$Installer->testConnection($cleanedConnection), 'Should connect');

		$connection['connection']['login'] = 'wrong';
		$this->assertFalse($Installer->testConnection($cleanedConnection), 'Should not connect');
	}

/**
 * test root install
 * 
 * @return void
 */
	public function testRootInstall() {
		$this->skipIf(true);
		App::uses('InstallerLib', 'Installer.Lib');
		$Installer = new InstallerLib();

		$cleanedConnection = $Installer->cleanConnectionDetails(array(
			'connection' => $this->db->config
		));
		$cleanedConnection['root'] = array(
			'login' => $this->db->config['login'],
			'password' => $this->db->config['password']
		);
		$this->assertFalse($Installer->testConnection($cleanedConnection), 'Should not connect');
	}

/**
 * @test installing plugins
 */
	public function testInstaller() {
		$this->skipIf(true);
		$this->__cleanSystem();

		App::uses('InstallerLib', 'Installer.Lib');
		App::uses('ReleaseVersion', 'Installer.Lib');

		$Installer = new InstallerLib();
		$Version = new ReleaseVersion(array('connection' => 'test_suite'));

		$connectionDetails = $Installer->cleanConnectionDetails(array('connection' => $this->db->config));
		$this->assertTrue($Installer->installPlugin($Version, $connectionDetails));
		$expected = array(
			'0' => 'acos',
			'1' => 'aros',
			'2' => 'aros_acos',
			'3' => 'schema_migrations',
			'4' => 'sessions',
		);
		$this->assertEqual($expected, $this->db->listSources());

		$this->assertTrue($Installer->installPlugin($Version, $connectionDetails, 'Installer'));
		$expected = array(
			'0' => 'acos',
			'1' => 'aros',
			'2' => 'aros_acos',
			'3' => 'core_plugins',
			'4' => 'schema_migrations',
			'5' => 'sessions',
		);
		$this->assertEqual($expected, $this->db->listSources());

		$pluginsToInstall = App::objects('plugin');
		natsort($pluginsToInstall);
		foreach($pluginsToInstall as $k => $pluginToInstall) {
			if(in_array($pluginToInstall, array('Migrations'))) {
				continue;
			}

			$this->assertTrue(
				$Installer->installPlugin($Version, $connectionDetails, $pluginToInstall),
				sprintf('%s could not be installed', $pluginToInstall)
			);
		}

		foreach($pluginsToInstall as $pluginToInstall) {
			$this->__checkVersionCount($pluginToInstall);
		}
	}

/**
 * drop all tables for testing the installer
 */
	private function __cleanSystem($all = true) {
		if(!isset($this->__oldTables)) {
			$this->__oldTables = ConnectionManager::getDataSource('test')->listSources();
		}
		foreach(ConnectionManager::getDataSource('test')->listSources() as $table) {
			if(!$all && in_array($table, $this->__oldTables)) {
				continue;
			}

			$this->Plugin->query('DROP TABLE ' . $table);
		}

		$this->assertEqual(array(), $this->Plugin->query('SHOW TABLES;'));

		ClassRegistry::flush();
	}

/**
 * check that the installed version matches the supplied releases
 */
	private function __checkVersionCount($plugin) {
		$path = CakePlugin::path($plugin) . 'Config' . DS . 'releases';
		$Folder = new Folder($path);
		$data = $Folder->read();
		$data = array_flip($data[1]);

		unset($data['map.php']);

		$installedVersions = ClassRegistry::init('Migrations.SchemaMigration')->find(
			'count',
			array(
				'conditions' => array(
					'SchemaMigration.type' => array(
						$plugin, strtolower($plugin)
					)
				)
			)
		);

		$this->assertIdentical(count($data), $installedVersions, sprintf('%s has %d releases but only installed %d of them', $plugin, count($data), $installedVersions));
	}
}