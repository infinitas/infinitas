<?php
	App::import('lib', 'Libs.InfinitasAppShell');
	
	class InfinitasReleaseStatusTask extends AppShell {
		public $tasks = array('Installer.Migration', 'Installer.InfinitasFixture');

		/**
		 * @brief list of plugins with the data that relates to them
		 * 
		 * @var array
		 */
		private $__plugins = array();

		/**
		 * @brief get the data and output the status
		 */
		public function execute() {
			$this->h1('Plugin Schema status');
			$this->__getStatus();
			$this->_output();
			$this->pause();
		}

		/**
		 * @breif display the data for all plugins
		 *
		 * Shows the status of plugins schema grouped by not installed, behind origin,
		 * installed with local changes and upto date
		 *
		 * @access protected
		 *
		 * @return void, outputs to terminal
		 */
		protected function _output(){
			$out = array('not-installed' => array(), 'behind' => array(), 'changes' => array(), 'ok' => array());
			foreach($this->__plugins as $plugin => $status){
				if(!$status['installed']){
					$out['not-installed'][] = $plugin;
				}

				else if($status['migrations_behind'] > 0){

					$row = sprintf(
						"%s	%s		%d/%d [%d]", str_pad($plugin, 15), ($status['installed']) ? '✔' : '☐',
						$status['migrations_installed'], $status['migrations_available'], $status['migrations_behind']
					);
					$out['behind'][] = $row;
				}

				else if ($status['changes']) {
					foreach($status['changes'] as $table => $actions){
						$text = array();
						foreach($actions as $action => $fields){
							$text[] = sprintf('[%s: %s]', $action, implode(', ', array_keys($fields)));
						}
						
						$out['changes'][] = sprintf("%s	%s	%s", str_pad($plugin, 15), str_pad($table, 15), implode(', ', $text));
					}
				}

				else {
					$out['ok'][] = $plugin;
				}
			}

			if(!empty($out['not-installed'])){
				$this->h2('Not Installed');
				$this->__outputList($out['not-installed']);
			}

			if(!empty($out['behind'])){
				$this->h2('Schema Behind');
				$this->out("Plugin		Installed	Migrations");
				foreach($out['behind'] as $row){
					$this->out($row);
				}
			}

			if(!empty($out['changes'])){
				$this->h2('Local Changes');
				$this->out("Plugin		Table		Fields");
				foreach($out['changes'] as $row){
					$this->Infinitas->out($row);
				}
			}

			if(!empty($out['ok'])){
				$this->h2('All Ok');
				$this->__outputList($out['ok']);
			}
		}

		/**
		 * @brief sort out data into something manageable
		 *
		 * @access protected
		 *
		 * @return void
		 */
		private function __getStatus(){
			$Plugin = ClassRegistry::init('Installer.Plugin');
			$allPlugins = $Plugin->getAllPlugins();

			foreach($allPlugins as $plugin){
				$this->interactive(sprintf('Checking %s', $plugin));
				if(in_array($plugin, array('Newsletter'))){
					continue;
				}

				$this->__plugins[$plugin] = $Plugin->getMigrationStatus($plugin);
				$this->interactive('.', true);

				$this->__plugins[$plugin]['changes'] = false;
				if($this->__plugins[$plugin]['installed'] && $this->__plugins[$plugin]['migrations_behind'] == 0){
					$this->__plugins[$plugin]['changes'] = $this->Migration->checkForChanges($plugin);
					$this->interactive('.', true);
				}
			}

			$this->interactiveClear();
		}

		/**
		 * @breif output list of things in cols
		 *
		 * generates cols of data based on the array that was passed in
		 *
		 * @param array $list array of data
		 */
		private function __outputList($list){
			$data = array();
			foreach($list as $row){
				$data[] = str_pad($row, 15);

				if(count($data) >= 4){
					$this->out(implode('', $data));
					$data = array();
				}
			}
			
			$this->out(implode('', $data));
		}
	}