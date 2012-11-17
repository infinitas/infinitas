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
	 * @package Infinitas.Libs.Console.Task
	 */
	class ProgressBarTask extends AppShell {

		/**
		 * Console Width
		 *
		 * @var integer
		 */
		public $terminalWidth = null;

		/**
		 * message displayed during updates
		 *
		 * @var string ''
		 */
		public $message = '';

		/**
		 * Maximum value on the bar
		 *
		 * @var integer
		 */
		public $total = 100;

		/**
		 * Size
		 *
		 * @var integer
		 */
		public $size = 25;

		/**
		 * Amount Completed
		 *
		 * @var integer
		 */
		public $done = 0;

		/**
		 * Start Time
		 *
		 * @var mixed
		 */
		public $startTime = null;

		/**
		 * String length for the previous line.  Used to overwrite hanging chars/
		 *
		 * @var integer
		 */
		public $strLenPrevLine = null;

		/**
		 * Execute the task - nothing to do by default
		 *
		 * @return void
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
		 */
		public function message($message = '') {
			$this->message = $message;
		}

		/**
		 * Increment the progress
		 *
		 * @return void
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
		 *
		 * @return integer
		 */
		public function out($message = null, $newlines = 1, $level = 1) {
			return parent::out($message, $newLines, $level);
		}

		/**
		 * Set the values and output
		 *
		 * @return void
		 */
		/**
		 * set method
		 *
		 * @param string $done Amount completed
		 * @param string $doneSize bar size
		 * @return void
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
					__('remaining'),
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
					__('remaining'),
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
		 *
		 * @return void
		 */
		public function start($total, $clear = true) {
			$this->total = $total;
			$this->done = 0;
			$this->startTime = time();
			$this->setTerminalWidth();
			if ($clear) {
				$this->out('', 1);
			}
		}

		/**
		 * Calculate remaining time in a nice format
		 *
		 * @return void
		 */
		protected function _niceRemaining() {
			$now = time();
			if($now == $this->startTime || $this->done == 0) {
				return '?';
			}

			$rate = ($this->startTime - $now) / $this->done;
			$remaining = -1 * round($rate * ($this->total - $this->done));

			if ($remaining < 60) {
				return sprintf('%02d %s', $remaining, __n('sec', 'secs', $remaining));
			} else {
				return sprintf('%d %s, %02d %s',
					floor($remaining / 60), __n('min', 'mins', floor($remaining / 60)),
					$remaining % 60, __n('sec', 'secs', $remaining % 60));
			}
		}

		/**
		 * setTerminalWidth method
		 *
		 * Ask the terminal, and default to min 80 chars.
		 *
		 * @TODO can you get windows to tell you the size of the terminal?
		 * @param mixed $width null
		 *
		 * @return void
		 */
		public function setTerminalWidth($width = null) {
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