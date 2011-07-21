<?php
	class InfinitasTask extends Shell{
		/**
		 * @brief width to wrap text to
		 */
		public $wrap = 70;
		
		/**
		 * @brief create a heading for infinitas shell stuff
		 */
		public function h1($title){
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
		public function h2($title){
			$this->out();
			$this->hr();
			$this->center($title, '|');
			$this->hr();
		}

		/**
		 * @brief create a heading for infinitas shell stuff
		 */
		public function h3($title){
			$this->out();
			$this->center($title);
			$this->hr();
		}

		/**
		 * @brief create nice paragraphs
		 */
		public function p($text){
			$this->out(wordwrap($text, 64));
			$this->out();
		}

		/**
		 * @brief center text
		 */
		public function center($text, $ends = ''){			
			$space1 = $space2 = str_repeat(' ', intval(($this->wrap - strlen($text)) / 2) -4);
			$this->out(sprintf('%s%s%s%s%s', $ends, $space1, $text, $space2, $ends));
		}

		/**
		 * @brief generate a list of options
		 */
		public function li($options = array()){
			if(!is_array($options)){
				$options = array($options);
			}

			foreach($options as $option){
				$this->out($option);
			}
		}

		/**
		 * @brief do a line break
		 *
		 * create a line break
		 */
		public function br(){
			$this->out();
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
		 * @brief pause help text when called from running shel
		 *
		 * When the comand is 'cake something help' its ok to just exit, if
		 * its 'cake something' and then the option [h] is used it should pause
		 * or the text is scrolled off the screen.
		 */
		public function helpPause(){
			if(!isset($this->Dispatch->shellCommand) || $this->Dispatch->shellCommand != 'help'){
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
		public function pause($text = 'Press a key to continue'){
			$this->br();
			$this->in($text);
		}

		public function color($text, $color){
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
			if(isset($_colors[$color])){
				$out = $_colors[$color];
			}

			return chr(27). $out . $text . chr(27) . "[0m";
		}

		/**
		 * @brief exit the shell
		 *
		 * Clear the screen and exit
		 */
		public function quit(){
			$this->clear();
			exit(0);
		}
	}
