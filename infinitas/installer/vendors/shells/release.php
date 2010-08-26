<?php

class ReleaseShell extends Shell {
	private $__info = array();

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
			/*$this->__info = array();

			$this->out("Initial release for " . $plugin);
			$this->hr();
			$this->out("It looks like this is the first time you are generating\nan Infinitas release for this plugin.");
			$this->__info['name'] = $this->in("Enter the name your plugin will be known as\n(I.E. what it will display in the Infinitas plugin manager).", null, $plugin);
			$this->__info['author'] = $this->in('Enter the plugin author name.');
			$this->__info['email'] = $this->in('Enter the plugin author email address.');
			$this->__info['website'] = $this->in('Enter the plugin website.');
			$this->__info['version'] = $this->in('Enter your initial version number.', null, '1.0');

			$dependancies = $this->in('Does this plugin have any non-core dependancies?', array('Y', 'N'), 'N');
			if(strtoupper($dependancies) == 'Y') {
				do {
					$this->__info['dependancies'][] = $this->in('Enter the dependancy name (blank to end).');
				} while(end($this->__info['dependancies']) != '');
				array_pop($this->__info['dependancies']);
			}

			$this->hr();
			$this->out($this->__info['name'] . ' (Version ' . $this->__info['version'] . ')');
			$this->hr();*/

			$this->__pluginModelConfig($plugin);
		}
	}

	private function __pluginModelConfig($plugin) {
		$models = App::objects('model', App::pluginPath($plugin) . 'models' . DS, false);

		pr($models);
	}
}
