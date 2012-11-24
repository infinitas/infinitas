<?php
/**
 * InfinitasPluginTask
 *
 * @package Infinitas.Installer.Console.Task
 */

/**
 * InfinitasPluginTask
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Console.Task
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 * @author dakota
 */

class InfinitasPluginTask extends AppShell {
/**
 * Shell tasks to load
 *
 * @var array
 */
	public $tasks = array(
		'Installer.Migration',
		'Installer.InfinitasFixture'
	);

/**
 * the plugin being released
 *
 * @var string
 */
	private $__plugin = null;

/**
 * the config path
 *
 * @var string
 */
	private $__configPath = null;

/**
 * Plugin info
 *
 * @var array
 */
	private $__info = array();

/**
 * Plugin models
 *
 * @var array
 */
	private $__models = array();

/**
 * Questions for building the release
 *
 * @var array
 */
	private $__options = array(
		'name' => 'What is the name of the plugin?',
		'author' => 'Who is the author of the plugin?',
		'website' => 'What is the main website of the plugin?',
		'description' => 'A short description of the plugin.',
		'license' => 'What license is the plugin released under?',
		'update_url' => 'What url should Infinitas check for version numbers?',
		'version' => 'What version is this release?'
	);

/**
 * Shell execute
 *
 * Start the release process
 *
 * @return void
 */
	public function execute() {
		$plugins = InfinitasPlugin::listPlugins('all');

		if (!empty($this->args)) {
			if (strtolower($this->args[0]) == 'all-core') {
				$this->args = InfinitasPlugin::listPlugins('core');
			}

			foreach ($this->args as $plugin) {
				if (in_array($plugin, $plugins) || strtolower($plugin) == 'app') {
					$this->generate($plugin);
					continue;
				}
				$this->out($plugin . ' plugin not found');
			}

			exit(0);
		}

		$this->generate(current(parent::_selectPlugins()));
	}

/**
 * generate a release for a plugin
 *
 * @param string $plugin Name of the plugin to generate the release for.
 *
 * @return void
 */
	public function generate($plugin) {
		$this->__plugin = $plugin;
		$this->__configPath = CakePlugin::path($this->__plugin) . 'Config' . DS;
		$this->__info = $this->__models = array();


		if (file_exists($this->__configPath . 'config.json')) {
			$configFile = new File($this->__configPath . 'config.json');
			$this->__info = Set::reverse(json_decode($configFile->read()));
			$this->__update();
		}

		else {
			App::import('core', 'String');
			$this->__info = array(
				'id' => String::uuid(),
				'name' => $this->__plugin,
				'update_url' => 'infinitas-cms.org/plugins/version/plugin:' . Inflector::underscore($this->__plugin),
				'version' => '1.0',
				'dependancies' => array()
			);

			$this->__initializeDependencies();
			$this->__initializeModels();

			$this->out("Initial release for " . $this->__plugin);
			$this->out("It looks like this is the first time you are generating\nan Infinitas release for this plugin.");

			do {
				$this->hr();

				$this->__initialInfo();

				$hasDependancies = !empty($this->__info['dependancies']) ? 'Y' : 'N';
				$dependancies = $this->in(
					'Does this plugin have any non-core dependancies?',
					array('Y', 'N'),
					$hasDependancies
				);
				if (strtoupper($dependancies) == 'Y') {
					$this->__configureDependancies();
				}

				$this->hr();
				$this->out($this->__info['name'] . ' (Version ' . $this->__info['version'] . ')');

				$this->__configureModels();
				$correct = $this->__reviewInformation();
			} while (strtoupper($correct) == 'N');

			if (strtoupper($correct) == 'Q') {
				return;
			}

			else {
				$this->__writeOut();
			}
		}
	}

/**
 * Output the release information
 *
 * @return void
 */
	private function __writeOut() {
		$this->hr();

		$this->out('Plugin: ' . $this->__plugin);
		$this->out('Generating migration...');
		$schemaMigration = $this->Migration->generate($this->__plugin);

		if (!empty($this->__models)) {
			$this->out('Generating fixtures...');
			$fixtures = $this->InfinitasFixture->generate($this->__models, $this->__plugin);
		}

		$this->__writeOutput(compact('schemaMigration', 'fixtures'));
	}

/**
 * initialise the plugins dependencies
 *
 * @return void
 */
	private function __initializeDependencies() {
		if (isset($this->params['dep'])) {
			$nonCorePlugins = $this->__getPluginList();
			$dependancies = explode(',', $this->params['dep']);

			foreach ($dependancies as $dependancy) {
				$dependancy = Inflector::camelize($dependancy);
				if (in_array($dependancy, $nonCorePlugins) && $dependancy != $plugin) {
					$this->__info['dependancies'][$dependancy] = true;
				}
			}
		}
	}

/**
 * initialise all the models for the current plugin
 *
 * @return void
 */
	private function __initializeModels() {
		if (isset($this->params['models']) && $this->params['models'] !== true) {
			$modelSetups = explode(',', $this->params['models']);

			$models = App::objects('model', CakePlugin::path($this->__plugin) . 'Model' . DS, false);

			$defaultSetup = array(
				'where' => '1=1',
				'limit' => '0'
			);

			foreach ($modelSetups as $modelSetup) {
				$modelSetup = explode('-', $modelSetup);
				$model = Inflector::classify($modelSetup[0]);

				if ($model == 'All') {
					foreach ($models as $model) {
						if (in_array('core', $modelSetup)) {
							$this->__models[$model]['core'] = $defaultSetup;
						}

						if (in_array('sample', $modelSetup)) {
							$this->__models[$model]['sample'] = $defaultSetup;
						}
					}

					break;
				}

				if (in_array($model, $models)) {
					if (in_array('core', $modelSetup)) {
						$this->__models[$model]['core'] = $defaultSetup;
					}

					if (in_array('sample', $modelSetup)) {
						$this->__models[$model]['sample'] = $defaultSetup;
					}
				}
			}
		}
	}

/**
 * Returns a list of available plugins
 *
 * @param boolean $searchAll True if we should return all plugins, false for only plugins in /plugins and plugin plugins.
 *
 * @return array
 */
	private function __getPluginList($searchType = 'Plugin') {
		$plugins = App::objects('plugin');
		natsort($plugins);

		if ($searchType == 'All') {
			return $plugins;
		}

		$infinitasPlugins = array();
		foreach ($plugins as $plugin) {
			$pluginPath = str_replace(APP, '', CakePlugin::path($plugin));

			if (strpos($pluginPath, $searchType) === 0) {
				$infinitasPlugins[] = $plugin;
			}
		}

		return $infinitasPlugins;
	}

/**
 * get information that needs updating when re-releasing a plugin
 *
 * @return array
 */
	private function __update() {
		do {
			$this->hr();
			$this->__displayInformation(true);
			$this->out('');
			$this->__displayDependancies(true);
			$this->hr();

			$update = $this->in('Do you wish to update any of this data (1 to 6, empty to continue, Q to exit)?');

			switch ($update) {
				case 1:
					$this->__info['name'] = $this->in("Plugin name.", null, $this->__info['name']);
					break;

				case 2:
					$this->__info['author'] = $this->in('Author name.', null, $this->__info['author']);
					break;

				case 3:
					$this->__info['email'] = $this->in('Author email address.', null, $this->__info['email']);
					break;

				case 4:
					$this->__info['website'] = $this->in('Plugin website.', null, $this->__info['website']);
					break;

				case 5:
					$this->__configureDependancies($plugin);
					break;
			}
		} while ($update != '' && strtoupper($update) != 'Q');

		if (strtoupper($update) == 'Q') {
			return;
		}

		else {
			$currentVersion = $this->__info['version'];

			do {
				$this->__info['version'] = $this->in('You are required to enter a new version number. Current version number is: ' . $this->__info['version']);
			} while ($this->__info['version'] == $currentVersion || $this->__info['version'] == '');


			$this->out('Generating migration...');
			$schemaMigration = $this->Migration->generate($this->__plugin);

			$this->__writeOutput(compact('schemaMigration', 'fixtures'));
		}
	}

/**
 * write the plugin release to disk
 *
 * @param array $options options to be written
 * @param boolean $writeConfig true to write the config
 *
 * @return void
 */
	private function __writeOutput($options = array(), $writeConfig = true) {
		extract($options);
		$class = 'R' . str_replace('-', '', String::uuid());
		$name = str_pad(intval(preg_replace('/[a-zA-Z._-]/', '', $this->__info['version'])), 6, '0', STR_PAD_LEFT) .
				'_' . $this->__plugin;

		if ($writeConfig) {
			$this->out('Writing config...');
			$this->__writeConfig();
		}

		$this->out('Writing release file...');
		$this->__writeRelease(compact('class', 'name', 'schemaMigration', 'fixtures'));

		$this->out('Writing release map...');
		$version = 1;

		$map = array();
		if (file_exists($this->__configPath . 'releases' . DS . 'map.php')) {
			include $this->__configPath . 'releases' . DS . 'map.php';
			ksort($map);
			end($map);

			list($version) = each($map);
			$version++;
		}

		$map[$version] = array($name => $class);
		$this->_writeMap($map);
		$this->out('Done.');
	}

/**
 * write the plugins config file
 *
 * @return boolean
 */
	private function __writeConfig() {
		$File = new File($this->__configPath . 'config.json', true);
		return $File->write(json_encode($this->__info));
	}

/**
 * write the release file
 *
 * @param array $options array of options for the release
 *
 * @return boolean
 */
	private function __writeRelease($options = array()) {
		extract(array_merge(array('fixtures' => null), $options));
		$vars = array_merge($this->__info, array('releaseName' => $name, 'class' => $class, 'migration' => $schemaMigration, 'plugin' => $this->__plugin));

		if (isset($fixtures)) {
			$vars['fixtures'] = $fixtures;
		}

		$content = $this->__generateTemplate('release', $vars);

		$this->__makeInstalled($this->__plugin);

		$File = new File($this->__configPath . 'releases' . DS . $name . '.php', true);
		return $File->write($content);
	}

/**
 * update the version in the database
 *
 * When creating migrations localy obviously your table is upto date so
 * it should be marked as such in the schema_migrations table so that there
 * are no errors later on with updates.
 *
 * This will also update/create the plugin details in the plugins table.
 *
 * @param string $plugin the name of the plugin
 *
 * @return boolean, true on save, false on error
 */
	private function __makeInstalled($plugin) {
		$SchemaMigration = ClassRegistry::init('SchemaMigration');

		$Plugin = ClassRegistry::init('Installer.Plugin');
		$Plugin->installPlugin($plugin, array('sampleData' => false, 'installRelease' => false));

		$migration = $SchemaMigration->find('first', array('conditions' => array('SchemaMigration.type' => $plugin)));

		$SchemaMigration->create();

		if (!empty($migration)) {
			unset($migration['SchemaMigration']['id']);
			$migration['SchemaMigration']['version'] += 1;
			return (bool)$SchemaMigration->save($migration);
		}

		return (bool)$SchemaMigration->save(array('SchemaMigration' => array('type' => $plugin, 'version' => 1)));
	}

/**
 * Generate and write the map file
 *
 * @param array $map List of migrations
 * @return boolean
 */
	protected function _writeMap($map) {
		$content = "<?php\n";
		$content .= "\$map = array(\n";

		foreach ($map as $version => $info) {
			list($name, $class) = each($info);
			$content .= "\t" . $version . " => array(\n";
			$content .= "\t\t'" . $name . "' => '" . $class . "'),\n";
		}

		$content .= ");\n";
		$content .= "?>";

		$File = new File($this->__configPath . 'releases' . DS . 'map.php', true);
		return $File->write($content);
	}

/**
 * Include and generate a template string based on a template file
 *
 * @param string $template Template file name
 * @param array $vars List of variables to be used on tempalte
 *
 * @return string
 */
	private function __generateTemplate($template, $vars) {
		extract($vars);
		ob_start();
		ob_implicit_flush(0);
		include(dirname(dirname(dirname(__FILE__))) . DS . 'Templates' . DS . $template . '.ctp');
		$content = ob_get_clean();

		return $content;
	}

/**
 * configure plugin models
 *
 * @return void
 */
	private function __configureModels() {
		$models = App::objects('model', CakePlugin::path($this->__plugin) . 'Model' . DS, false);

		if (empty($this->__models)) {
			$this->__models = array_fill_keys($models, array());
		}

		do {
			$this->hr();
			$this->out('We have detected the following models in your plugin.');

			$i = 1;
			foreach ($this->__models as $model => $info) {
				$label = $i++ . '. ' . $model;
				if (isset($info['core'])) {
					$label .= ' - Core data included';
				}

				if (isset($info['sample'])) {
					$label .= ' - Sample data included';
				}
				$this->out($label);
			}

			$model = $this->in('Select the models for which you wish to have a data dump included in your release (nothing to finish).') - 1;

			if (isset($models[$model])) {
				$this->__configureModel($models[$model]);
			}
		} while ($model >= 0);
	}

/**
 * configure the model information
 *
 * @param string $model the model being configured
 *
 * @return void
 */
	private function __configureModel($model) {
		$this->hr();
		$coreData = strtoupper($this->in('Do you wish to include a core data dump?', array('Y', 'N'), 'Y'));
		if ($coreData == 'Y') {
			$this->__models[$model]['core']['where'] = $this->in('Enter a sql conditions snippit for the core data.', null, '1 = 1');
			$this->__models[$model]['core']['limit'] = $this->in('Enter a limit for the core data (0 = no limit).', null, 0);
		}

		$sampleData = strtoupper($this->in('Do you wish to include a sample data dump?', array('Y', 'N'), 'N'));
		if ($sampleData == 'Y') {
			$this->__models[$model]['sample']['where'] = $this->in('Enter a sql conditions snippit for the sample data.', null, '1 = 1');
			$this->__models[$model]['sample']['limit'] = $this->in('Enter a limit for the sample data (0 = no limit).', null, 0);
		}

		if (count($this->__models) > 1) {
			$copy = strtoupper($this->in('Copy these settings to other models?', array('Y', 'N'), 'N'));

			if ($copy == 'Y') {
				$copyFrom = $this->__models[$model];
				foreach ($this->__models as $model => $settings) {
					$this->__models[$model] = $copyFrom;
				}
			}
		}
	}

/**
 * display plugin details before confirming
 *
 * @return string
 */
	private function __reviewInformation() {
		$this->hr();
		$this->__displayInformation();
		$this->out('');
		$this->__displayDependancies();
		$this->out('');
		$this->__displayModels();
		$this->hr();

		return $this->in('Is this information correct (Q to cancel)?', array('Y', 'N', 'Q'), 'Y');
	}

/**
 * display plugin info
 *
 * @param boolean $asMenu display as menu (true) or info (false)
 *
 * @return void
 */
	private function __displayInformation($asMenu = false) {
		$this->out('Plugin internal name: ' . $this->__plugin);
		foreach ($this->__options as $option => $question) {
			$this->out('Plugin ' . $option . ":\t" . $this->__info[$option]);
		}
	}

/**
 * display dependancies for the plugin
 *
 * @param boolean $asMenu display as menu (true) or info (false)
 *
 * @return void
 */
	private function __displayDependancies($asMenu = false) {
		$text = ($asMenu) ? '5. ' : '' . 'Dependancies: ';
		$this->out($text);
		if (!empty($this->__info['dependancies'])) {
			foreach ($this->__info['dependancies'] as $dependancy => $value) {
				$this->out("\t* " . $dependancy);
			}
			return;
		}

		$this->out("\t None");
	}

/**
 * display the models detected in the plugin
 *
 * @return void
 */
	private function __displayModels() {
		$this->out('Models: ');
		foreach ($this->__models as $model => $info) {
			$label = "\t* " . $model;
			if (isset($info['core'])) {
				$label .= ' - Core data included';
			}

			if (isset($info['sample'])) {
				$label .= ' - Sample data included';
			}
			$this->out($label);
		}
	}

/**
 * get any plugin dependancies
 *
 * @return void
 */
	private function __configureDependancies() {
		$possiblePlugins = $this->__getPluginList();
		do {
			$i = 1;
			$pluginLookup = array();
			foreach ($possiblePlugins as $key => $possiblePlugin) {
				if ($possiblePlugin != $this->__plugin) {
					$pluginLookup[$i] = $possiblePlugin;
					$label = $i++ . '. ' . $possiblePlugin;

					if (isset($this->__info['dependancies'][$possiblePlugin])) {
						$label .= ' *';
					}

					$this->out($label);
				}
			}

			$dependancy = $this->in('Select the plugins this plugin depends on (Blank to end).');
			if (isset($pluginLookup[$dependancy])) {
				if (!isset($this->__info['dependancies'][$pluginLookup[$dependancy]])) {
					$this->__info['dependancies'][$pluginLookup[$dependancy]] = true;
				}

				else {
					unset($this->__info['dependancies'][$pluginLookup[$dependancy]]);
				}
			}
		} while ($dependancy != '');
	}

/**
 * get the details about the plugin being released
 *
 * @return void
 */
	private function __initialInfo() {
		foreach ($this->__options as $option => $question) {
			$default = isset($this->__info[$option]) ? $this->__info[$option] : null;

			if (is_numeric($option)) {
				$question = 'Enter the plugin ' . $option;
			}

			$this->__info[$option] = $this->in($question, null, $default);
		}
	}

}