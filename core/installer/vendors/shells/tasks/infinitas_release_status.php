<?php
	class InfinitasReleaseStatusTask extends Shell {
		public $tasks = array('Migration', 'InfinitasFixture', 'Infinitas');

		private $__plugins = array();

		public function execute() {
			Configure::write('debug', 2);

			$this->Infinitas->h1('Plugin Schema status');

			$this->__getStatus();

			$this->_output();

			$this->Infinitas->pause();
		}

		private function __getStatus(){
			$Plugin = ClassRegistry::init('Installer.Plugin');
			$allPlugins = $Plugin->getAllPlugins();

			foreach($allPlugins as $plugin){
				if(in_array($plugin, array('Newsletter'))){
					continue;
				}
				$this->__plugins[$plugin] = $Plugin->getMigrationStatus($plugin);

				$this->__plugins[$plugin]['changes'] = false;
				if($this->__plugins[$plugin]['installed'] && $this->__plugins[$plugin]['migrations_behind'] == 0){
					$this->__plugins[$plugin]['changes'] = $this->Migration->checkForChanges($plugin);
				}
			}
		}

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

			$this->Infinitas->h2('Not Installed');
			$this->__outputList($out['not-installed']);

			$this->Infinitas->h2('Schema Behind');
			$this->Infinitas->out("Plugin		Installed	Migrations");
			foreach($out['behind'] as $row){
				$this->Infinitas->out($row);
			}

			$this->Infinitas->h2('Local Changes');
			$this->Infinitas->out("Plugin		Table		Fields");
			foreach($out['changes'] as $row){
				$this->Infinitas->out($row);
			}

			$this->Infinitas->h2('All Ok');
			$this->__outputList($out['ok']);
		}

		private function __outputList($list){
			$data = array();
			foreach($list as $row){
				$data[] = str_pad($row, 15);

				if(count($data) >= 4){
					$this->Infinitas->out(implode('', $data));
					$data = array();
				}
			}
			$this->Infinitas->out(implode('', $data));
		}
	}