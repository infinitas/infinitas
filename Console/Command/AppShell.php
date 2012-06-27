<?php
	/**
	* AppShell file
	*/

	App::uses('Shell', 'Console');

	/**
	* Application Shell
	*
	* Add your application-wide methods in the class below, your shells
	* will inherit them.
	*/

	class AppShell extends Shell {
		public function main($title = null) {
			if($title === null) {
				$title = Inflector::humanize(Inflector::underscore(str_replace('Shell', '', get_class($this))));
			}

			if(!$title) {
				return;
			}

			$this->h1($title);
		}

		/**
		 * @brief width to wrap text to
		 */
		public $wrap = 70;

		/**
		 * @brief the width of the terminal
		 *
		 * @var int
		 */
		public $terminalWidth = 25;

		private $__interactiveBuffer = null;

		public function __construct($stdout = null, $stderr = null, $stdin = null) {
			$this->setTerminalWidth();

			App::import('Core', 'Router');
			InfinitasPlugin::loadForInstaller();

			parent::__construct($stdout, $stderr, $stdin);

			$this->__verbose = isset($this->params['verbose']) && $this->params['verbose'];
		}

		/**
		 * @brief create a heading for infinitas shell stuff
		 */
		public function h1($title) {
			$this->clear();
			$this->out("         _____        __ _       _ _");
			$this->out("        |_   _|	     / _(_)     (_) |");
			$this->out("          | |  _ __ | |_ _ _ __  _| |_  __ _ ___");
			$this->out("          | | | '_ \|  _| | '_ \| | __|/ _` / __|");
			$this->out("          | |_| | | | | | | | | | | |_| (_| \__ \ ");
			$this->out("        |_____|_| |_|_| |_|_| |_|_|\__|\__,_|___/ " . Configure::read('Infinitas.version'));
			$this->h2($title);
		}

		/**
		 * @brief create a heading for infinitas shell stuff
		 */
		public function h2($title) {
			$this->out();
			$this->hr();
			$this->center($title, '|');
			$this->hr();
		}

		/**
		 * @brief create a heading for infinitas shell stuff
		 */
		public function h3($title) {
			$this->out();
			$this->center($title);
			$this->hr();
		}

		/**
		 * @brief create nice paragraphs
		 */
		public function p($text) {
			$this->out(wordwrap($text, 64));
			$this->out();
		}

		/**
		 * @brief center text
		 */
		public function center($text, $ends = '') {
			$space1 = $space2 = str_repeat(' ', intval(($this->wrap - strlen($text)) / 2) -4);
			$this->out(sprintf('%s%s%s%s%s', $ends, $space1, $text, $space2, $ends));
		}

		/**
		 * @brief generate a list of options
		 */
		public function li($options = array()) {
			if(!is_array($options)) {
				$options = array($options);
			}

			foreach($options as $option) {
				$this->out($option);
			}
		}

		/**
		 * @brief do a line break
		 *
		 * create a line break
		 */
		public function br() {
			$this->out();
		}

		/**
		 * @brief pause help text when called from running shel
		 *
		 * When the comand is 'cake something help' its ok to just exit, if
		 * its 'cake something' and then the option [h] is used it should pause
		 * or the text is scrolled off the screen.
		 */
		public function helpPause() {
			if(!isset($this->Dispatch->shellCommand) || $this->Dispatch->shellCommand != 'help') {
				$this->pause();
			}
		}

		/**
		 * @brief pause the page and wait for some input before carrying on
		 *
		 * Useful for stopping the page so the user can see what the output is
		 * before returing to the main menu.
		 *
		 * @access public
		 *
		 * @param string $text the text to output when pausing.
		 */
		public function pause($text = 'Press a key to continue') {
			$this->br();
			$this->in($text);
		}

		public function color($text, $color) {
			$_colors = array(
				'light_red' => "[1;31m",
				'light_green' => "[1;32m",
				'yellow' => "[1;33m",
				'light_blue' => "[1;34m",
				'magenta' => "[1;35m",
				'light_cyan' => "[1;36m",
				'white' => "[1;37m",
				'normal' => "[0m",
				'black' => "[0;30m",
				'red' => "[0;31m",
				'green' => "[0;32m",
				'brown' => "[0;33m",
				'blue' => "[0;34m",
				'cyan' => "[0;36m",
				'bold' => "[1m",
				'underscore' => "[4m",
				'reverse'=> "[7m",

			);

			$out = "[0m";
			if(isset($_colors[$color])) {
				$out = $_colors[$color];
			}

			return chr(27). $out . $text . chr(27) . "[0m";
		}

		public function interactiveClear() {
			$this->stdout->write("\r" . str_repeat(' ', $this->terminalWidth - 2), 0);
			$this->__interactiveBuffer = null;
		}

		public function interactive($text = null, $append = false) {
			if($append === true) {
				$this->__interactiveBuffer .=  $text;
			}
			else{
				$this->__interactiveBuffer = $text;
			}

			$this->stdout->write("\r" . str_pad($this->__interactiveBuffer, $this->terminalWidth - 2), 0);
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
		 * @brief exit the shell
		 *
		 * Clear the screen and exit with an optional message that will be shown
		 * after the screen is cleared.
		 *
		 * @return void
		 */
		public function quit($message = 'Bye :)') {
			self::clear();

			if($message) {
				self::out($message);
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
			if(!$items || !array($items)) {
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
					self::out(implode('', $out));
					$out = array();
				}
			}

			if(!empty($out)) {
				self::out(implode('', $out));
			}

			self::out('');
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
		protected function _selectPlugins($allowAll = false, $filter = 'all') {
			self::h1('Select a plugin');
			$filter = Inflector::underscore((string)$filter);
			if(!$filter) {
				$filter = 'all';
			}

			$plugins = $this->__getPlugins($filter);

			if($allowAll) {
				$plugins['A'] = 'all';
				$plugins['B'] = 'back';
			}

			self::tabbedList($plugins);
			$availableOptions = array_keys($plugins);

			$option = null;
			while(!$option) {
				$option = strtoupper($this->in(__('Which plugin should be used?')));
			}

			if($option == 'B') {
				return array();
			}

			else if($allowAll && $option == 'A') {
				unset($plugins['A'], $plugins['B']);
				return $plugins;
			}

			else if(isset($plugins[$option])) {
				return array($plugins[$option]);
			}
			else{
				$options = explode(',', $option);
				$return = array();
				foreach($options as $option) {
					if(isset($plugins[$option])) {
						$return[] = $plugins[$option];
					}
				}

				if(!empty($return)) {
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
		protected function _selectModels($plugin, $allowAll = false) {
			self::h1('Select a model');

			$models = $this->__getModels($plugin);

			if($allowAll) {
				$models['A'] = 'all';
				$models['B'] = 'back';
			}
			$this->tabbedList($models);
			$availableOptions = array_keys($models);

			$option = null;
			while(!$option) {
				$option = strtoupper($this->in(__('Which model should be used?')));
			}

			if($option == 'B') {
				return array();
			}

			else if($allowAll && $option == 'A') {
				unset($models['A'], $models['B']);
				return $models;
			}

			else if(isset($models[$option])) {
				return array($models[$option]);
			}
			else{
				$options = explode(',', $option);
				$return = array();
				foreach($options as $option) {
					if(isset($models[$option])) {
						$return[] = $models[$option];
					}
				}

				if(!empty($return)) {
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
		private function __getPlugins($filter) {
			$Plugin = ClassRegistry::init('Installer.Plugin');
			$plugins = array();

			switch($filter) {
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
