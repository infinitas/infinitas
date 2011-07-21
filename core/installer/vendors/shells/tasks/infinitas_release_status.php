<?php
	class InfinitasReleaseStatusTask extends Shell {
		public $tasks = array('Migration', 'InfinitasFixture', 'Infinitas');

		public function execute() {
			Configure::write('debug', 2);

			$this->Infinitas->h1('Plugin Schema status');

			$this->_outputUninstalled();
			$this->_outputChanges();

			$this->Infinitas->pause();
		}

		/**
		 * @brief get a list of plugins that have not been installed yet.
		 *
		 * This method compares a list of plugins that are found on the system
		 * with the list of plugins that have been 'officially' installed through
		 * the installer.
		 *
		 * @return void
		 */
		protected function _outputUninstalled(){
			$this->Infinitas->h2('Uninstalled plugins');
			$allPlugins = ClassRegistry::init('Installer.Plugin')->getAllPlugins();
			$uninstalled = array_diff($allPlugins, array_values(ClassRegistry::init('Installer.Plugin')->getInstalledPlugins()));
			sort($uninstalled);

			$out = array();
			foreach($uninstalled as $i => $plugin){
				$out[] = str_pad(sprintf('%d) %s', str_pad(++$i, 3, ' ', STR_PAD_LEFT), $plugin), 20);

				if(count($out) == 3){
					$this->Infinitas->out(implode('', $out));
					$out = array();
				}
			}
			
			$this->Infinitas->out(implode('', $out));
			$this->Infinitas->out(sprintf('Total Plugins: %d Not yet installed: %d', count($allPlugins), count($uninstalled)));
			$this->Infinitas->out();
		}

		/**
		 * @brief otuput a list of possible schema changes
		 *
		 * This will attempt to find and show any changes that have been made and
		 * not released per plugin. Its almost like 'git diff' for the plugin schema
		 *
		 * @access protected
		 *
		 * @return void
		 */
		protected function _outputChanges(){
			$this->Infinitas->h2('Plugins with schema changes');
			$allPlugins = ClassRegistry::init('Installer.Plugin')->getAllPlugins();

			$return = array();
			foreach($allPlugins as $plugin) {
				if(in_array($plugin, array('Newsletter'))){
					continue;
				}
				
				$plugin = Inflector::camelize($plugin);
				$change = $this->Migration->checkForChanges($plugin);
				if(!empty($change)){
					$return[$plugin] = $change;
				}
			}

			$pluginCount = count($allPlugins);
			$count = 0;
			$out = array();
			foreach($return as $plugin => $error){
				unset($allPlugins[$plugin]);

				$out[] = sprintf('%d] %s : %s', ++$count, str_pad($plugin, 15, ' '), $error['error']);
			}

			if(empty($out)){
				$out[] = 'No changes in schema detected';
			}

			$out[] = sprintf('Total Plugins: %d Outdated Schemas: %d', $pluginCount, count($out));

			foreach($out as $o){
				$this->Infinitas->out($o);
			}
			$this->Infinitas->out();
		}
	}