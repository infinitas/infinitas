<?php
	class InfinitasReleaseStatusTask extends Shell {
		public $tasks = array('Migration', 'InfinitasFixture', 'Infinitas');

		public function execute() {
			Configure::write('debug', 2);
			$plugins = $this->__getPluginList();

			$return = array();
			foreach($plugins as $plugin) {
				if(in_array($plugin, array('Newsletter'))){
					continue;
				}
				$plugin = Inflector::camelize($plugin);
				$change = $this->Migration->checkForChanges($plugin);
				if(!empty($change)){
					$return[$plugin] = $change;
				}
			}

			$this->_output($return);
			$this->Infinitas->pause();
		}

		protected function _output($data){
			$allPlugins = array_flip($this->__getPluginList());
			$pluginCount = count($allPlugins);
			$count = 0;
			$out = array();
			foreach($data as $plugin => $error){
				unset($allPlugins[$plugin]);

				$out[] = sprintf('%d] %s : %s', ++$count, str_pad($plugin, 15, ' '), $error['error']);
			}

			$out[] = sprintf('Total Plugins: %d Outdated Schemas: %d', $pluginCount, count($out));

			$this->Infinitas->h1('Plugin Schema status');
			foreach($out as $o){
				$this->Infinitas->out($o);
			}
		}


		/**
		 * Returns a list of available plugins
		 *
		 * @param boolean $searchAll True if we should return all plugins, false for only plugins in /plugins and plugin plugins.
		 * @return array Array of available plugins
		 */
		private function __getPluginList() {
			$plugins = App::objects('plugin');
			natsort($plugins);

			return $plugins;
		}
	}