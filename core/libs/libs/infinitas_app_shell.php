<?php
	class InfinitasAppShell extends Shell {
		public $tasks = array(
			'ProgressBar',
			'Infinitas'
		);

		/**
		 * @brief the width of the terminal
		 * 
		 * @var int
		 */
		public $terminalWidth = 25;

		private $__interactiveBuffer = null;

		public function __construct($dispatch){
			$this->__bootstrapPlugins();
			$this->setTerminalWidth();

			App::import('Core', 'Router');

			parent::__construct($dispatch);
		}

		public function interactiveClear() {
			$this->Dispatch->stdout("\r" . str_repeat(' ', $this->terminalWidth - 2), false);
			$this->__interactiveBuffer = null;
		}

		public function interactive($text = null, $append = false) {
			if($append === true){
				$this->__interactiveBuffer .=  $text;
			}
			else{
				$this->__interactiveBuffer = $text;
			}

			$this->Dispatch->stdout("\r" . str_pad($this->__interactiveBuffer, $this->terminalWidth - 2), false);
		}

		private function __bootstrapPlugins() {
			if(!class_exists('Folder')){
				App::import('Folder');
			}

			$Folder = new Folder(APP);
			$folders = $Folder->read();
			$folders = array_flip($folders[0]);
			unset($Folder, $folders['.git'], $folders['config'], $folders['locale'],
				$folders['nbproject'], $folders['tmp'], $folders['views'],
				$folders['webroot'], $folders['tests']);

			$paths = array();
			foreach(array_flip($folders) as $folder){
				$paths[] = APP . $folder . DS;
			}

			Cache::write('plugin_paths', $paths);
			unset($Folder, $folders);

			App::build(
				array(
					'plugins' => $paths
				)
			);

			unset($paths);
		}

		/**
		 * setTerminalWidth method
		 *
		 * Ask the terminal, and default to min 80 chars.
		 *
		 * @TODO can you get windows to tell you the size of the terminal?
		 * @param mixed $width null
		 * @return void
		 * @access protected
		 */
		protected function setTerminalWidth($width = null) {
			if ($width === null) {
				if (DS === '/') {
					$width = `tput cols`;
				}
				if ($width < 80) {
					$width = 80;
				}
			}

			$this->size = isset($this->size) ? $this->size : 25;

			$this->size = min(max(4, $width / 10), $this->size);
			$this->terminalWidth = $width;
		}

		/**
		 * @brief clear the page
		 *
		 * clear the screen
		 */
		public function clear(){
			$this->Dispatch->clear();
		}

		/**
		 * @brief exit the shell
		 *
		 * Clear the screen and exit with an optional message that will be shown
		 * after the screen is cleared.
		 *
		 * @return void
		 */
		public function quit($message = 'Bye :)'){
			$this->clear();

			if($message){
				$this->out($message);
			}

			exit(0);
		}

		/**
		 * @brief generate a pretty list for output in the shell
		 *
		 * @access public
		 *
		 * @param array $items list of items to output
		 *
		 * @return void
		 */
		public function tabbedList($items = array()) {
			if(!$items || !array($items)){
				return false;
			}

			$longestItem = max(array_map('strlen', $items)) + 6;

			$perLine = round($this->terminalWidth / $longestItem) - 1;

			$out = array();
			foreach($items as $k => $item) {
				$k = str_pad($k, 3, ' ', STR_PAD_LEFT);
				$item = Inflector::humanize(Inflector::underscore($item));
				$out[] = str_pad(sprintf('%s] %s', $k, $item), $longestItem, ' ', STR_PAD_RIGHT);

				if(count($out) >= $perLine) {
					$this->Infinitas->out(implode('', $out));
					$out = array();
				}
			}

			if(!empty($out)) {
				$this->Infinitas->out(implode('', $out));
			}

			$this->Infinitas->out('');
		}

		/**
		 * @brief generate a list of possible plugins with various filters
		 *
		 * This will generate a list of plugins based on the filter passed. See
		 * InfinitasAppShell::__getPlugins() for a list of possible filters
		 *
		 * @access protected
		 *
		 * @param bool $allowAll allow selecting all (mass updates etc)
		 * @param string $filter see InfinitasAppShell::__getPlugins()
		 *
		 * @return the list of plugins found
		 */
		protected function _selectPlugins($allowAll = false, $filter = 'all'){
			$this->Infinitas->h1('Select a plugin');
			$filter = Inflector::underscore((string)$filter);
			if(!$filter){
				$filter = 'all';
			}
			
			$plugins = $this->__getPlugins($filter);

			if($allowAll){
				$plugins['A'] = 'all';
				$plugins['B'] = 'back';
			}
			$this->tabbedList($plugins);
			$availableOptions = array_keys($plugins);

			$option = null;
			while(!$option){
				$option = strtoupper($this->in(__('Which plugin should be used?', true)));
			}

			if($option == 'B'){
				return array();
			}
			
			else if($allowAll && $option == 'A'){
				unset($plugins['A'], $plugins['B']);
				return $plugins;
			}
			
			else if(isset($plugins[$option])) {
				return array($plugins[$option]);
			}
			else{
				$options = explode(',', $option);
				$return = array();
				foreach($options as $option){
					if(isset($plugins[$option])){
						$return[] = $plugins[$option];
					}
				}

				if(!empty($return)){
					return $return;
				}
			}

			$this->_selectPlugins($allowAll, $filter);
		}

		/**
		 * @brief get a list of models for a specific plugin
		 *
		 * @access protected
		 *
		 * @param string $plugin the name of the plugin to get models from
		 * @param bool $allowAll true for allow selecting all, false for only selecting one
		 *
		 * @return array list of models (even if only allow selecting one)
		 */
		protected function _selectModels($plugin, $allowAll = false){
			$this->Infinitas->h1('Select a model');

			$models = $this->__getModels($plugin);

			if($allowAll){
				$models['A'] = 'all';
				$models['B'] = 'back';
			}
			$this->tabbedList($models);
			$availableOptions = array_keys($models);

			$option = null;
			while(!$option){
				$option = strtoupper($this->in(__('Which model should be used?', true)));
			}

			if($option == 'B'){
				return array();
			}

			else if($allowAll && $option == 'A'){
				unset($models['A'], $models['B']);
				return $models;
			}

			else if(isset($models[$option])) {
				return array($models[$option]);
			}
			else{
				$options = explode(',', $option);
				$return = array();
				foreach($options as $option){
					if(isset($models[$option])){
						$return[] = $models[$option];
					}
				}

				if(!empty($return)){
					return $return;
				}
			}

			$this->_selectModels($plugin, $allowAll);
		}


		/**
		 * @brief get plugins based on the filter
		 *
		 * This will generate a list of plugins based on the filter passed. The
		 * filter can be one of the following
		 * - all: will use App::objects('plugin')
		 * - installed: will check with the system for things that are installed
		 * - not_installed: the difference between installed and all
		 * - changed: get changed plugins only (installed but updated)
		 *
		 * @param string $filter as above
		 *
		 * @return array of plugins found
		 */
		private function __getPlugins($filter){
			$Plugin = ClassRegistry::init('Installer.Plugin');
			$plugins = array();
			
			switch($filter){
				case 'all':
					$plugins = $Plugin->getAllPlugins();
					break;

				case 'installed':
					$plugins = $Plugin->getInstalledPlugins();
					break;

				case 'not_installed':
					$plugins = $Plugin->getNonInstalledPlugins();
					break;

				case 'changed':
					$plugins = $Plugin->getNonInstalledPlugins();
					break;

				case 'core':
					break;

				case 'non_core':
					break;
			}

			unset($Plugin, $filter);
			natsort($plugins);

			return array_combine(range(1, count($plugins)), $plugins);
		}

		/**
		 * @brief find a list of models for a specified plugin
		 *
		 * @access private
		 *
		 * @param string $plugin the name of the plugin
		 *
		 * @return array the list of models
		 */
		private function __getModels($plugin) {
			$models = App::objects('model', App::pluginPath($plugin) . 'models');
			natsort($models);
			return array_combine(range(1, count($models)), $models);
		}
	}