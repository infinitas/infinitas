<?php
class PluginTask extends Shell {
	public $tasks = array('Migration', 'Fixture');
	private $__plugin = null;
	private $__configPath = null;
	private $__info = array();
	private $__models = array();
	private $__options = array(
		'name',
		'author',
		'email',
		'website',
		'version'
	);

	public function execute() {
		$plugins = $this->__getPluginList(isset($this->params['all']) || !empty($this->args));

		if(!empty($this->args)) {
			foreach($this->args as $plugin) {
				$plugin = Inflector::camelize($plugin);

				if(in_array($plugin, $plugins)) {
					$this->generate($plugin);
					exit(0);
				}
				else {
					$this->out($plugin . ' plugin not found');
				}
			}
		}

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
				$this->generate($plugins[$plugin]);
			}
		} while($plugin > 0);
	}

	/**
	 *
	 * @param string $plugin Name of the plugin to generate the release for.
	 * @return Nothing
	 */
	public function generate($plugin) {
		$pluginPath = App::pluginPath($plugin);
		$this->__plugin = $plugin;
		$this->__info = $this->__models = array();

		$this->__configPath = $pluginPath . DS . 'config' . DS;

		if(file_exists($this->__configPath . 'config.json')) {
			$configFile = new File($this->__configPath . 'config.json');
			$this->__info = Set::reverse(json_decode($configFile->read()));
			$this->__update();
		}
		else {
			$this->__info = array(
				'dependancies' => array()
			);
			do {
				if(!isset($this->params['silent'])) {
					$this->out("Initial release for " . $this->__plugin);
					$this->hr();
					$this->out("It looks like this is the first time you are generating\nan Infinitas release for this plugin.");
					$this->__info['name'] = $this->__plugin;
					$this->__info['version'] = '1.0';

					$this->__initialInfo();

					$dependancies = $this->in('Does this plugin have any non-core dependancies?', array('Y', 'N'), !empty($this->__info['dependancies']) ? 'Y' : 'N');
					if(strtoupper($dependancies) == 'Y') {
						$this->__configureDependancies();
					}

					$this->hr();
					$this->out($this->__info['name'] . ' (Version ' . $this->__info['version'] . ')');

					$this->__configureModels();
					$correct = $this->__reviewInformation();
				}
				else {
					foreach($this->__options as $option) {
						$this->__info[$option] = isset($this->params[$option]) ? $this->params[$option] : '';
					}

					if(isset($this->params['dep'])) {
						$dependancies = explode($this->params['dep']);
					}
				}
			} while(strtoupper($correct) == 'N');

			if(strtoupper($correct) == 'Q') {
				return;
			}
			else {
				$this->hr();

				$this->out('Generating migration...');
				$schemaMigration = $this->Migration->generate($this->__plugin);

				$this->out('Generating fixtures...');
				$fixtures = $this->Fixture->generate($this->__models, $this->__plugin);

				$this->__writeOutput(compact('schemaMigration', 'fixtures'));
			}
		}
	}



	/**
	 * Returns a list of available plugins
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

	private function __writeOutput($options = array()) {
		extract($options);

		$class = 'R' . str_replace('-', '', String::uuid());
		$name = str_pad(intval(preg_replace('/[a-zA-Z._-]/', '', $this->__info['version'])), 4, '0', STR_PAD_LEFT) . '_' . Inflector::underscore($this->__plugin);

		$this->out('Writing config...');
		$this->__writeConfig();

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

	private function __update() {
		do {
			$this->hr();
			$this->__displayInformation(true);
			$this->out('');
			$this->__displayDependancies(true);
			$this->hr();

			$update = $this->in('Do you wish to update any of this data (1 to 6, empty to continue, Q to exit)?');

			switch($update) {
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
		} while($update != '' && strtoupper($update) != 'Q');

		if(strtoupper($update) == 'Q') {
			return;
		}
		else {
			$currentVersion = $this->__info['version'];

			do {
				$this->__info['version'] = $this->in('You are required to enter a new version number.');
			} while($this->__info['version'] == $currentVersion || $this->__info['version'] == '');


			$this->out('Generating migration...');
			$schemaMigration = $this->Migration->generate($this->__plugin);

			$this->__writeOutput(compact('schemaMigration', 'fixtures'));
		}
	}

	private function __writeConfig() {
		$jsonConfig = json_encode($this->__info);

		$File = new File($this->__configPath . 'config.json', true);
		return $File->write($jsonConfig);
	}

	private function __writeRelease($options = array()) {
		extract(array_merge(array('fixtures' => null), $options));

		$vars = array_merge($this->__info, array('releaseName' => $name, 'class' => $class, 'migration' => $schemaMigration, 'fixtures' => $fixtures, 'plugin' => $this->__plugin));

		$content = $this->__generateTemplate('release', $vars);

		$File = new File($this->__configPath . 'releases' . DS . $name . '.php', true);
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

		$File = new File($this->__configPath . 'releases' . DS . 'map.php', true);
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

	private function __configureModels() {
		$models = App::objects('model', App::pluginPath($this->__plugin) . 'models' . DS, false);

		if(empty($this->__models)) {
			$this->__models = array_fill_keys($models, array());
		}

		do {
			$this->hr();
			$this->out('We have detected the following models in your plugin.');

			$i = 1;
			foreach($this->__models as $model => $info) {
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
				$this->__configureModel($models[$model]);
			}
		} while($model >= 0);
	}

	private function __configureModel($model) {
		$this->hr();
		$coreData = strtoupper($this->in('Do you wish to include a core data dump?', array('Y', 'N'), 'Y'));
		if($coreData == 'Y') {
			$this->__models[$model]['core']['where'] = $this->in('Enter a sql conditions snippit for the core data.', null, '1 = 1');
			$this->__models[$model]['core']['limit'] = $this->in('Enter a limit for the core data (0 = no limit).', null, 0);
		}

		$sampleData = strtoupper($this->in('Do you wish to include a sample data dump?', array('Y', 'N'), 'N'));
		if($sampleData == 'Y') {
			$this->__models[$model]['sample']['where'] = $this->in('Enter a sql conditions snippit for the sample data.', null, '1 = 1');
			$this->__models[$model]['sample']['limit'] = $this->in('Enter a limit for the sample data (0 = no limit).', null, 0);
		}

		if(count($this->__models) > 1) {
			$copy = strtoupper($this->in('Copy these settings to other models?', array('Y', 'N'), 'N'));

			if($copy == 'Y') {
				$copyFrom = $this->__models[$model];
				foreach($this->__models as $model => $settings) {
					$this->__models[$model] = $copyFrom;
				}
			}
		}
	}

	private function __displayInformation($asMenu = false) {
		$this->out('Plugin internal name: ' . $this->__plugin);
		foreach($this->__options as $option) {
			$this->out('Plugin ' . $option . ":\t" . $this->__info[$option]);
		}
	}

	private function __displayDependancies($asMenu = false) {
		$this->out(($asMenu ? '5. ' : '') . 'Dependancies: ');
		if(!empty($this->__info['dependancies'])) {
			foreach($this->__info['dependancies'] as $dependancy => $value) {
				$this->out("\t* " . $dependancy);
			}
		}
		else {
			$this->out("\t None");
		}
	}

	private function __displayModels() {
		$this->out('Models: ');
		foreach($this->__models as $model => $info) {
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

	private function __configureDependancies() {
		$possiblePlugins = $this->__getPluginList();
		do {
			$i = 1;
			$pluginLookup = array();
			foreach($possiblePlugins as $key => $possiblePlugin) {
				if($possiblePlugin != $this->__plugin) {
					$pluginLookup[$i] = $possiblePlugin;
					$label = $i++ . '. ' . $possiblePlugin;
					if(isset($this->__info['dependancies'][$possiblePlugin])) {
						$label .= ' *';
					}

					$this->out($label);
				}
			}

			$dependancy = $this->in('Select the plugins this plugin depends on (Blank to end).');
			if(isset($pluginLookup[$dependancy])) {
				if(!isset($this->__info['dependancies'][$pluginLookup[$dependancy]])) {
					$this->__info['dependancies'][$pluginLookup[$dependancy]] = true;
				}
				else {
					unset($this->__info['dependancies'][$pluginLookup[$dependancy]]);
				}
			}
		} while($dependancy != '');
	}

	private function __initialInfo() {
		foreach($this->__options as $option) {
			$default = isset($this->__info[$option]) ? $this->__info[$option] : (isset($this->params[$option]) ? $this->params[$option] : '');
			$this->__info[$option] = $this->in('Enter the plugin ' . $option, null, $default);
		}
	}
}

?>
