<?php

class ReleaseShell extends Shell {
	public $tasks = array('Migration', 'Fixture');
	private $__info = array();
	private $__models = array();
	private $__options = array(
		'name',
		'author',
		'email',
		'website',
		'version'
	);

	public function main() {
		do {
			$this->out('Interactive Release Shell');
			$this->hr();
			$this->out('[P]lugin');
			$this->out('[M]odule');
			$this->out('[T]heme');
			$this->out('Plugin [A]dd-on');
			$this->out('[Q]uit');

			$input = strtoupper($this->in('What do yolu wish to release?'));

			switch ($input) {
				case 'P':
					$this->plugin();
					break;
				case 'M':
					break;
				case 'T':
					break;
				case 'A':
					break;
				case 'Q':
					exit(0);
					break;
				default:
					$this->out('Invalid option');
					break;
			}
		} while($input != 'Q');
	}

	public function plugin() {
		$plugins = $this->__getPluginList(isset($this->params['all']) || isset($this->params['name']));

		if(isset($this->params['name'])) {
			$plugin = Inflector::classify($this->params['name']);

			if(in_array($plugin, $plugins)) {
				$this->__generateRelease($plugin);
			}
		}
		else {
			do {
				$this->out("Select plugin");
				$this->hr();

				foreach($plugins as $key => $plugin) {
					$this->out($key+1 . '. ' . $plugin);
				}

				$plugin = $this->in('Which plugin do you want to create a new release for (nothing to return)?') - 1;

				if($plugin < 0) {
					return;
				}
				elseif(isset($plugins[$plugin])) {
					$this->__generateRelease($plugins[$plugin]);
				}
			} while($plugin > 0);
		}
	}

	/**
	 *
	 * @param boolean $searchAll True if we should return all plugins, false for only plugins in /plugins and plugin plugins.
	 * @return array Array of available plugins 
	 */
	private function __getPluginList($searchAll = false) {
		$plugins = App::objects('plugin');

		if($searchAll) {
			return $plugins;
		}
		
		$infinitasPlugins = array();
		foreach($plugins as $plugin) {
			$pluginPath = str_replace(APP, '', App::pluginPath($plugin));
			if(strpos($pluginPath, 'plugins') === 0) {
				$infinitasPlugins[] = $plugin;
			}
		}

		return $infinitasPlugins;
	}

	/**
	 *
	 * @param string $plugin Name of the plugin to generate the release for.
	 * @return Nothing
	 */
	private function __generateRelease($plugin) {
		$pluginPath = App::pluginPath($plugin);

		$this->configPath = $pluginPath . DS . 'config' . DS;

		if(file_exists($this->configPath . 'config.json')) {
			$configFile = new File($this->configPath . 'config.json');
			$this->__info[$plugin] = Set::reverse(json_decode($configFile->read()));
			$this->__updatePlugin($plugin);
		}
		else {
			$this->__info[$plugin] = array(
				'dependancies' => array()
			);
			do {
				$this->out("Initial release for " . $plugin);
				$this->hr();
				$this->out("It looks like this is the first time you are generating\nan Infinitas release for this plugin.");
				$this->__initialPluginInfo($plugin);

				$dependancies = $this->in('Does this plugin have any non-core dependancies?', array('Y', 'N'), !empty($this->__info[$plugin]['dependancies']) ? 'Y' : 'N');
				if(strtoupper($dependancies) == 'Y') {
					$this->__configureDependancies($plugin);
				}

				$this->hr();
				$this->out($this->__info[$plugin]['name'] . ' (Version ' . $this->__info[$plugin]['version'] . ')');

				$this->__pluginModelConfig($plugin);
				$correct = $this->__reviewInformation($plugin);
			} while(strtoupper($correct) == 'N');

			if(strtoupper($correct) == 'Q') {
				return;
			}
			else {
				$this->hr();

				$this->out('Generating migration...');
				$schemaMigration = $this->Migration->generate($plugin);

				$this->out('Generating fixtures...');
				$fixtures = $this->Fixture->generate($this->__models[$plugin], $plugin);

				$this->__writeOutput($plugin, compact('schemaMigration', 'fixtures'));
			}
		}
	}

	private function __writeOutput($plugin, $options = array()) {
		extract($options);
		
		$class = 'R' . str_replace('-', '', String::uuid());
		$name = str_pad(intval(preg_replace('/[a-zA-Z._-]/', '', $this->__info[$plugin]['version'])), 4, '0', STR_PAD_LEFT) . '_' . Inflector::underscore($plugin);

		$this->out('Writing config...');
		$this->__writeConfig($plugin);

		$this->out('Writing release file...');
		$this->__writeRelease($plugin, compact('class', 'name', 'schemaMigration', 'fixtures'));

		$this->out('Writing release map...');
		$version = 1;
		$map = array();
		if (file_exists($this->configPath . 'releases' . DS . 'map.php')) {
			include $this->configPath . 'releases' . DS . 'map.php';
			ksort($map);
			end($map);

			list($version) = each($map);
			$version++;
		}
		$map[$version] = array($name => $class);

		$this->_writeMap($map);

		$this->out('Done.');
	}

	private function __updatePlugin($plugin) {
		do {
			$this->hr();
			$this->__displayPluginInformation($plugin, true);
			$this->out('');
			$this->__displayDependancies($plugin, true);
			$this->hr();

			$update = $this->in('Do you wish to update any of this data (1 to 6, empty to continue, Q to exit)?');

			switch($update) {
				case 1:
					$this->__info[$plugin]['name'] = $this->in("Plugin name.", null, $this->__info[$plugin]['name']);
					break;
				case 2:
					$this->__info[$plugin]['author'] = $this->in('Author name.', null, $this->__info[$plugin]['author']);
					break;
				case 3:
					$this->__info[$plugin]['email'] = $this->in('Author email address.', null, $this->__info[$plugin]['email']);
					break;
				case 4:
					$this->__info[$plugin]['website'] = $this->in('Plugin website.', null, $this->__info[$plugin]['website']);
					break;
				case 5:
					$this->__configureDependancies($plugin);
					break;
			}
		} while($update != '' && strtoupper($update) != 'Q');

		if(strtoupper($update) == 'Q') {
			return;
		}
		else {
			$currentVersion = $this->__info[$plugin]['version'];

			do {
				$this->__info[$plugin]['version'] = $this->in('You are required to enter a new version number.');
			} while($this->__info[$plugin]['version'] == $currentVersion || $this->__info[$plugin]['version'] == '');


			$this->out('Generating migration...');
			$schemaMigration = $this->Migration->generate($plugin);

			$this->__writeOutput($plugin, compact('schemaMigration', 'fixtures'));
		}
	}

	private function __writeConfig($plugin) {
		$jsonConfig = json_encode($this->__info[$plugin]);

		$File = new File($this->configPath . 'config.json', true);
		return $File->write($jsonConfig);
	}

	private function __writeRelease($plugin, $options = array()) {
		extract(array_merge(array('fixtures' => null), $options));

		$content = $this->__generateTemplate('release', array('name' => $name, 'class' => $class, 'migration' => $schemaMigration, 'fixtures' => $fixtures));

		$File = new File($this->configPath . 'releases' . DS . $name . '.php', true);
		return $File->write($content);
	}

/**
 * Generate and write the map file
 *
 * @param array $map List of migrations
 * @return boolean
 * @access protected
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

		$File = new File($this->configPath . 'releases' . DS . 'map.php', true);
		return $File->write($content);
	}

/**
 * Include and generate a template string based on a template file
 *
 * @param string $template Template file name
 * @param array $vars List of variables to be used on tempalte
 * @return string
 * @access private
 */
	private function __generateTemplate($template, $vars) {
		extract($vars);
		ob_start();
		ob_implicit_flush(0);
		include(dirname(__FILE__) . DS . 'templates' . DS . $template . '.ctp');
		$content = ob_get_clean();

		return $content;
	}

	private function __pluginModelConfig($plugin) {
		$models = App::objects('model', App::pluginPath($plugin) . 'models' . DS, false);

		if(empty($this->__models[$plugin])) {
			$this->__models[$plugin] = array_fill_keys($models, array());
		}
		
		do {
			$this->hr();
			$this->out('We have detected the following models in your plugin.');

			$i = 1;
			foreach($this->__models[$plugin] as $model => $info) {
				$label = $i++ . '. ' . $model;
				if(isset($info['core'])) {
					$label .= ' - Core data included';
				}
				if(isset($info['sample'])) {
					$label .= ' - Sample data included';
				}
				$this->out($label);
			}

			$model = $this->in('Select the models for which you wish to have a data dump included in your release (nothing to finish).') - 1;

			if(isset($models[$model])) {
				$this->__configureModel($plugin, $models[$model]);
			}
		} while($model >= 0);
	}

	private function __configureModel($plugin, $model) {
		$this->hr();
		$coreData = strtoupper($this->in('Do you wish to include a core data dump?', array('Y', 'N'), 'Y'));
		if($coreData == 'Y') {
			$this->__models[$plugin][$model]['core']['where'] = $this->in('Enter a sql conditions snippit for the core data.', null, '1 = 1');
			$this->__models[$plugin][$model]['core']['limit'] = $this->in('Enter a limit for the core data (0 = no limit).', null, 0);
		}

		$sampleData = strtoupper($this->in('Do you wish to include a sample data dump?', array('Y', 'N'), 'N'));
		if($sampleData == 'Y') {
			$this->__models[$plugin][$model]['sample']['where'] = $this->in('Enter a sql conditions snippit for the sample data.', null, '1 = 1');
			$this->__models[$plugin][$model]['sample']['limit'] = $this->in('Enter a limit for the sample data (0 = no limit).', null, 0);
		}
	}

	private function __displayPluginInformation($plugin, $asMenu = false) {
		$this->out('Plugin internal name: ' . $plugin);
		foreach($this->__options as $option) {
			$this->out('Plugin ' . $option . ":\t" . $this->__info[$plugin][$option]);
		}
	}

	private function __displayDependancies($plugin, $asMenu = false) {
		$this->out(($asMenu ? '5. ' : '') . 'Dependancies: ');
		if(!empty($this->__info[$plugin]['dependancies'])) {
			foreach($this->__info[$plugin]['dependancies'] as $dependancy => $value) {
				$this->out("\t* " . $dependancy);
			}
		}
		else {
			$this->out("\t None");
		}
	}

	private function __displayModels($plugin) {
		$this->out('Models: ');
		foreach($this->__models[$plugin] as $model => $info) {
			$label = "\t* " . $model;
			if(isset($info['core'])) {
				$label .= ' - Core data included';
			}
			if(isset($info['sample'])) {
				$label .= ' - Sample data included';
			}
			$this->out($label);
		}
	}

	private function __reviewInformation($plugin) {
		$this->hr();
		$this->__displayPluginInformation($plugin);
		$this->out('');
		$this->__displayDependancies($plugin);
		$this->out('');
		$this->__displayModels($plugin);
		$this->hr();

		return $this->in('Is this information correct (Q to cancel)?', array('Y', 'N', 'Q'), 'Y');
	}

	private function __configureDependancies($plugin) {
		$possiblePlugins = $this->__getPluginList();
		do {
			$i = 1;
			$pluginLookup = array();
			foreach($possiblePlugins as $key => $possiblePlugin) {
				if($possiblePlugin != $plugin) {
					$pluginLookup[$i] = $possiblePlugin;
					$label = $i++ . '. ' . $possiblePlugin;
					if(isset($this->__info[$plugin]['dependancies'][$possiblePlugin])) {
						$label .= ' *';
					}

					$this->out($label);
				}
			}

			$dependancy = $this->in('Select the plugins this plugin depends on (Blank to end).');
			if(isset($pluginLookup[$dependancy])) {
				if(!isset($this->__info[$plugin]['dependancies'][$pluginLookup[$dependancy]])) {
					$this->__info[$plugin]['dependancies'][$pluginLookup[$dependancy]] = true;
				}
				else {
					unset($this->__info[$plugin]['dependancies'][$pluginLookup[$dependancy]]);
				}
			}
		} while($dependancy != '');
	}

	private function __initialPluginInfo($plugin) {
		foreach($this->__options as $option) {
			$default = isset($this->__info[$plugin][$option]) ? $this->__info[$plugin][$option] : (isset($this->params[$option]) ? $this->params[$option] : null);
			$this->__info[$plugin][$option] = $this->in('Enter the plugin ' . $option, null, $default);
		}
	}
}

