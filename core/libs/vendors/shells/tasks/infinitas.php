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
			$this->Dispatch->clear();
			$this->out("         _____        __ _       _ _");
			$this->out("        |_   _|      / _(_)     (_) |");
			$this->out("          | |  _ __ | |_ _ _ __  _| |_  __ _ ___");
			$this->out("          | | | '_ \|  _| | '_ \| | __|/ _` / __|");
			$this->out("         _| |_| | | | | | | | | | | |_| (_| \__ \ ");
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
	}
