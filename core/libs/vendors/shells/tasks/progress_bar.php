<?php
	/*
	 * CakePHP shell task for doing a simple progress bar
	 * Copyright (c) 2010 Matt Curry
	 * www.PseudoCoder.com
	 * http://github.com/mcurry/progress_bar
	 *
	 * @author      Matt Curry <matt@pseudocoder.com>
	 * @license     MIT
	 *
	 */

	/**
	 * ProgressBarTask class
	 *
	 * @uses          Shell
	 * @package       progress_bar
	 * @subpackage    progress_bar.vendors.shells.tasks
	 */
	class ProgressBarTask extends Shell {

		/**
		 * Console Width
		 *
		 * @var int
		 * @access public
		 */
		public $terminalWidth = null;

		/**
		 * message displayed during updates
		 *
		 * @var string ''
		 * @access public
		 */
		public $message = '';

		/**
		 * Maximum value on the bar
		 *
		 * @var int
		 * @access public
		 */
		public $total = 100;

		/**
		 * Size
		 *
		 * @var int
		 * @access public
		 */
		public $size = 25;

		/**
		 * Amount Completed
		 *
		 * @var int
		 * @access public
		 */
		public $done = 0;

		/**
		 * Start Time
		 *
		 * @var mixed
		 * @access public
		 */
		public $startTime = null;

		/**
		 * String length for the previous line.  Used to overwrite hanging chars/
		 *
		 * @var int
		 * @access public
		 */
		public $strLenPrevLine = null;

		/**
		 * Execute the task - nothing to do by default
		 *
		 * @return void
		 * @access public
		 */
		public function execute() {
		}

		/**
		 * finish method
		 *
		 * Set to 100% - useful as a last call after a loop
		 * if you don't know the exact number of steps it's going to take
		 *
		 * @return void
		 * @access public
		 */
		public function finish() {
			if ($this->done < $this->total) {
				$this->set(null, $this->size);
			}
		}

		/**
		 * Set the message to be used during updates
		 *
		 * @param string $message ''
		 * @return void
		 * @access public
		 */
		public function message($message = '') {
			$this->message = $message;
		}

		/**
		 * Increment the progress
		 *
		 * @return void
		 * @access public
		 */
		public function next($inc = 1) {
			$this->done += $inc;
			$this->set();
		}

		/**
		 * Overrides standard shell output to allow /r without /n
		 *
		 * Outputs a single or multiple messages to stdout. If no parameters
		 * are passed outputs just a newline.
		 *
		 * @param mixed $message A string or a an array of strings to output
		 * @param integer $newlines Number of newlines to append
		 * @return integer Returns the number of bytes returned from writing to stdout.
		 * @access public
		 */
		public function out($message = null, $newLines = 0) {
			return parent::out($message, $newLines);
		}

		/**
		 * Set the values and output
		 *
		 * @return void
		 * @access public
		 */
		/**
		 * set method
		 *
		 * @param string $done Amount completed
		 * @param string $doneSize bar size
		 * @return void
		 * @access public
		 */
		public function set($done = null, $doneSize = null) {
			if ($done) {
				$this->done = min($done, $this->total);
			}

			$this->total = max(1, $this->total);
			$perc = round($this->done / $this->total, 3);
			if ($doneSize === null) {
				$doneSize = floor(min($perc, 1) * $this->size);
			}
			$message = $this->message;
			if ($message) {
				$output = sprintf(
					"%.01f%% %d/%d %s %s [%s>%s]",
					$perc * 100,
					$this->done, $this->total,
					$this->_niceRemaining(),
					__('remaining', true),
					str_repeat("-", $doneSize),
					str_repeat(" ", $this->size - $doneSize)
				);
				$width = strlen($output);

				if (strlen($message) > ($this->terminalWidth - $width - 3)) {
					$message = substr($message, 0, ($this->terminalWidth - $width - 4)) . '...';
				}
				$message = str_pad($message, ($this->terminalWidth - $width));
			} else {
				$output = sprintf(
					"[%s>%s] %.01f%% %d/%d %s %s",
					str_repeat("-", $doneSize),
					str_repeat(" ", $this->size - $doneSize),
					$perc * 100,
					$this->done, $this->total,
					$this->_niceRemaining(),
					__('remaining', true),
					$this->done, $this->total
				);
			}

			$this->out("\r" . $message . $output);
			flush();
		}

		/**
		 * Start a progress bar
		 *
		 * @param string $total Total value of the progress bar
		 * @return void
		 * @access public
		 */
		public function start($total, $clear = true) {
			$this->total = $total;
			$this->done = 0;
			$this->startTime = time();
			$this->_setTerminalWidth();
			if ($clear) {
				$this->out('', 1);
			}
		}

		/**
		 * Calculate remaining time in a nice format
		 *
		 * @return void
		 * @access public
		 */
		protected function _niceRemaining() {
			$now = time();
			if($now == $this->startTime || $this->done == 0) {
				return '?';
			}

			$rate = ($this->startTime - $now) / $this->done;
			$remaining = -1 * round($rate * ($this->total - $this->done));

			if ($remaining < 60) {
				return sprintf('%02d %s', $remaining, __n('sec', 'secs', $remaining, true));
			} else {
				return sprintf('%d %s, %02d %s',
					floor($remaining / 60), __n('min', 'mins', floor($remaining / 60), true),
					$remaining % 60, __n('sec', 'secs', $remaining % 60, true));
			}
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
		protected function _setTerminalWidth($width = null) {
			if ($width === null) {
				if (DS === '/') {
					$width = `tput cols`;
				}
				if ($width < 80) {
					$width = 80;
				}
			}
			$this->size = min(max(4, $width / 10), $this->size);
			$this->terminalWidth = $width;
		}
	}