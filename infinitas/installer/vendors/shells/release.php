<?php

class ReleaseShell extends Shell {
	private $__info = array();
	private $__models = array();

	public function main() {
		do {
			$this->out('Interactive Release Shell');
			$this->hr();
			$this->out('[P]lugin');
			$this->out('[M]odule');
			$this->out('[T]heme');
			$this->out('[Q]uit');

			$input = strtoupper($this->in('What do you wish to release?'));

			switch ($input) {
				case 'P':
					$this->plugin();
					break;
				case 'M':
					break;
				case 'T':
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
		do {
			$this->out("Select plugin");
			$this->hr();

			$plugins = $this->__getPluginList(isset($this->params['all']));
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

	private function __getPluginList($searchAll = false) {
		$plugins = App::objects('plugin');

		if($searchAll) {
			return $plugins;
		}
		
		$infinitasPlugins = array();
		foreach($plugins as $plugin) {
			$pluginPath = str_replace(APP, '', App::pluginPath($plugin));

			if(stristr($pluginPath, 'plugins')) {
				$infinitasPlugins[] = $plugin;
			}
		}

		return $infinitasPlugins;
	}

	private function __generateRelease($plugin) {
		$pluginPath = App::pluginPath($plugin);

		if(file_exists($pluginPath . DS . 'config' . DS . 'config.json')) {

		}
		else {
			$this->__info[$plugin] = array(
				'dependancies' => array()
			);
			do {
				$this->out("Initial release for " . $plugin);
				$this->hr();
				$this->out("It looks like this is the first time you are generating\nan Infinitas release for this plugin.");
				$this->__info[$plugin]['name'] = $this->in("Enter the name your plugin will be known as\n(I.E. what it will display in the Infinitas plugin manager).", null, isset($this->__info[$plugin]['name']) ? $this->__info[$plugin]['name'] : $plugin);
				$this->__info[$plugin]['author'] = $this->in('Enter the plugin author name.', null, isset($this->__info[$plugin]['author']) ? $this->__info[$plugin]['author'] : null);
				$this->__info[$plugin]['email'] = $this->in('Enter the plugin author email address.', null, isset($this->__info[$plugin]['email']) ? $this->__info[$plugin]['email'] : null);
				$this->__info[$plugin]['website'] = $this->in('Enter the plugin website.', null, isset($this->__info[$plugin]['website']) ? $this->__info[$plugin]['website'] : null);
				$this->__info[$plugin]['version'] = $this->in('Enter your initial version number.', null, isset($this->__info[$plugin]['version']) ? $this->__info[$plugin]['version'] : '1.0');

				$dependancies = $this->in('Does this plugin have any non-core dependancies?', array('Y', 'N'), !empty($this->__info[$plugin]['dependancies']) ? 'Y' : 'N');
				if(strtoupper($dependancies) == 'Y') {
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

				$this->hr();
				$this->out($this->__info[$plugin]['name'] . ' (Version ' . $this->__info[$plugin]['version'] . ')');

				$this->__pluginModelConfig($plugin);
				$correct = $this->__reviewInformation($plugin);
			} while(strtoupper($correct) == 'N');
		}
	}

	private function __pluginModelConfig($plugin) {
		$models = App::objects('model', App::pluginPath($plugin) . 'models' . DS, false);

		$this->__models[$plugin] = array_fill_keys($models, array());
		
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

	private function __reviewInformation($plugin) {
		$this->hr();
		$this->out('Plugin internal name: ' . $plugin);
		$this->out('Plugin name:          ' . $this->__info[$plugin]['name']);
		$this->out('Author name:          ' . $this->__info[$plugin]['author']);
		$this->out('Author email address: ' . $this->__info[$plugin]['email']);
		$this->out('Plugin website:       ' . $this->__info[$plugin]['website']);
		$this->out('Plugin version:       ' . $this->__info[$plugin]['version']);

		if(!empty($this->__info[$plugin]['dependancies'])) {
			$this->out('Dependancies: ');
			foreach($this->__info[$plugin]['dependancies'] as $dependancy => $value) {
				$this->out("\t* " . $dependancy);
			}
		}
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
		$this->hr();

		return $this->in('Is this information correct?', array('Y', 'N'), 'Y');
	}
}

